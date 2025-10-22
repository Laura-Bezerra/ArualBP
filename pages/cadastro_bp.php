<?php 
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';

$setor_id = $_POST['setor_id'] ?? ($_GET['setor_id'] ?? '');

// 🔹 Buscar setores
// ID e nível do usuário logado
$usuario_id = $_SESSION['id'] ?? null;
$nivel_acesso = $_SESSION['nivel_acesso'] ?? '';

// Se for ADMIN: pode ver todos os setores
if ($nivel_acesso === 'admin') {
    $sqlSetores = "SELECT * FROM setores ORDER BY nome ASC";
}
// Se for GERENTE: mostra apenas setores em que ele é gerente
elseif ($nivel_acesso === 'gerente') {
    $sqlSetores = "SELECT * FROM setores WHERE gerente_id = $usuario_id ORDER BY nome ASC";
}
// Se for USUÁRIO comum: mostra apenas setores em que ele está vinculado
else {
    $sqlSetores = "
        SELECT s.* 
        FROM setores s
        INNER JOIN setor_usuarios su ON su.setor_id = s.id
        WHERE su.usuario_id = $usuario_id
        ORDER BY s.nome ASC
    ";
}

$resultSetores = $conexao->query($sqlSetores);

// 🔹 Buscar BPs do setor selecionado
$sqlBens =$sqlBens = $setor_id 
    ? "SELECT bps.*, categorias.nome AS nome_categoria 
       FROM bps 
       LEFT JOIN categorias ON bps.categoria_id = categorias.id 
       WHERE bps.setor_id = '$setor_id'"
    : "SELECT bps.*, categorias.nome AS nome_categoria 
       FROM bps 
       LEFT JOIN categorias ON bps.categoria_id = categorias.id 
       WHERE 1=0";
$resultBens = $conexao->query($sqlBens);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="../css/cadastro_bp.css">
    <link rel="stylesheet" href="../css/modal_bp.css">
    <link rel="stylesheet" href="../css/modal_info_bp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Cadastro e Gerenciamento de BPs</h2>
    
    <!-- Filtro por setor -->
    <form method="POST" class="form-inline mb-3">
        <label for="setor">Selecione o Setor:</label>
        <select name="setor_id" id="setor" class="form-control">
            <option value="">Selecione o setor</option>
            <?php while ($setor = $resultSetores->fetch_assoc()): ?>
                <option value="<?= $setor['id'] ?>" <?= $setor['id'] == $setor_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($setor['nome']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>

    <!-- Botão de adicionar -->
    <?php if (!empty($setor_id)): ?>
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
            Adicionar Novo BP
        </button>   
    <?php endif; ?>

    <!-- Tabela de bens -->
    <table class="table table-hover table-bordered">
        <thead class="thead-roxa">
            <tr>
            <th>ID</th>
            <th>Nome do Item</th>
            <th>Descrição</th>
            <th>Marca</th>
            <th>Data de Aquisição</th>
            <th>Estado</th>
            <th>Custo Total (R$)</th>
            <th>Local</th>
            <th>Etiqueta</th>
            <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($bp = $resultBens->fetch_assoc()): ?>
            <tr>
            <td><?= $bp['id'] ?></td>
            <td><?= htmlspecialchars($bp['nome_item']) ?></td>
            <td><?= htmlspecialchars($bp['descricao']) ?></td>
            <td><?= htmlspecialchars($bp['marca']) ?></td>
            <td><?= !empty($bp['data_aquisicao']) ? date('d/m/Y', strtotime($bp['data_aquisicao'])) : '-' ?></td>
            <td><?= htmlspecialchars($bp['estado_item']) ?></td>
            <td><?= number_format($bp['custo_total'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($bp['local']) ?></td>
            <td>
            <button type="button"
                    class="btn-etiqueta"
                    data-bs-toggle="modal"
                    data-bs-target="#etiquetaModal"
                    data-bp-id="<?= $bp['id'] ?>">
                <?= htmlspecialchars($bp['codigo_bp']) ?>
            </button>
            </td>
            <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                <!-- Botão de informações -->
                <button class="btn btn-info btn-sm infoBtn"
                        data-bs-toggle="modal"
                        data-bs-target="#infoModal"
                        data-id="<?= $bp['id'] ?>"
                        data-codigo_bp="<?= htmlspecialchars($bp['codigo_bp']) ?>"
                        data-nome_item="<?= htmlspecialchars($bp['nome_item']) ?>"
                        data-descricao="<?= htmlspecialchars($bp['descricao']) ?>"
                        data-marca="<?= htmlspecialchars($bp['marca']) ?>"
                        data-categoria="<?= htmlspecialchars($bp['nome_categoria'] ?? 'Sem categoria') ?>"
                        data-quantidade="<?= htmlspecialchars($bp['quantidade']) ?>"
                        data-data_aquisicao="<?= htmlspecialchars($bp['data_aquisicao']) ?>"
                        data-fornecedor="<?= htmlspecialchars($bp['fornecedor']) ?>"
                        data-condicao_aquisicao="<?= htmlspecialchars($bp['condicao_aquisicao']) ?>"
                        data-estado_item="<?= htmlspecialchars($bp['estado_item']) ?>"
                        data-custo_unitario="<?= htmlspecialchars($bp['custo_unitario']) ?>"
                        data-custo_total="<?= htmlspecialchars($bp['custo_total']) ?>"
                        data-local="<?= htmlspecialchars($bp['local']) ?>"
                        data-observacoes="<?= htmlspecialchars($bp['observacoes']) ?>">
                    <i class="fa-solid fa-circle-info"></i>
                </button>

                <!-- Editar -->
                <button class="btn btn-success btn-sm editBtn" 
                        data-bs-toggle="modal"
                        data-bs-target="#editModal"
                        data-id="<?= $bp['id'] ?>"
                        data-nome_item="<?= htmlspecialchars($bp['nome_item']) ?>"
                        data-descricao="<?= htmlspecialchars($bp['descricao']) ?>"
                        data-marca="<?= htmlspecialchars($bp['marca']) ?>"
                        data-categoria_id="<?= $bp['categoria_id'] ?>"
                        data-quantidade="<?= $bp['quantidade'] ?>"
                        data-custo_unitario="<?= $bp['custo_unitario'] ?>"
                        data-custo_total="<?= $bp['custo_total'] ?>"
                        data-data_aquisicao="<?= $bp['data_aquisicao'] ?>"
                        data-fornecedor="<?= htmlspecialchars($bp['fornecedor']) ?>"
                        data-local="<?= htmlspecialchars($bp['local']) ?>"
                        data-condicao_aquisicao="<?= htmlspecialchars($bp['condicao_aquisicao']) ?>"
                        data-estado_item="<?= htmlspecialchars($bp['estado_item']) ?>"
                        data-observacoes="<?= htmlspecialchars($bp['observacoes']) ?>">
                    <i class="fa-solid fa-pen"></i>
                </button>

                <!-- Excluir -->
                <a href="../actions/bp_actions.php?delete_id=<?= $bp['id'] ?>&setor_id=<?= $setor_id ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Deseja excluir este item e todas as etiquetas associadas?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
                </div>
            </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
</table>
</div>

<?php include '../includes/modal_etiqueta.php'; ?>
<?php include '../includes/modal_bp.php'; ?>
<?php include '../includes/modal_info_bp.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/cadastro_bp.js"></script>
<script src="../js/etiquetas.js"></script>

<script>
  document.querySelectorAll('select').forEach(sel => {
    sel.addEventListener('mousedown', e => {
      sel.style.position = 'relative';
      sel.style.zIndex = '99999';
    });
    sel.addEventListener('blur', e => {
      sel.style.zIndex = '1';
    });
  });
</script>

</body>
</html>
