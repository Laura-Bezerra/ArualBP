<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['id']) || !in_array($_SESSION['nivel_acesso'], ['gerente', 'admin'])) {
    header('Location: login.php');
    exit();
}

$gerente_id = $_SESSION['id'];

// ===== FILTROS =====
$filtro_setor = $_GET['setor'] ?? '';
$filtro_usuario = $_GET['usuario'] ?? '';

// ===== LISTAS PARA FILTROS =====
// 🔹 Setores do gerente
$sqlSetores = "SELECT id, nome FROM setores WHERE gerente_id = '$gerente_id' ORDER BY nome ASC";
$resultSetores = $conexao->query($sqlSetores);

// 🔹 Usuários desses setores
$sqlUsuarios = "
    SELECT DISTINCT u.id, u.nome 
    FROM usuarios u
    JOIN setor_usuarios su ON su.usuario_id = u.id
    JOIN setores st ON su.setor_id = st.id
    WHERE st.gerente_id = '$gerente_id'
    ORDER BY u.nome ASC
";
$resultUsuarios = $conexao->query($sqlUsuarios);

// ===== CONSULTA PRINCIPAL =====
$sql = "
    SELECT 
        s.id,
        u.nome AS usuario_nome,
        st.nome AS setor_nome,
        b.nome_item AS nome_item,
        s.campo_alterado,
        s.valor_atual,
        s.novo_valor,
        s.descricao AS motivo,
        s.tipo,
        s.status,
        s.data_solicitacao,
        s.data_aprovacao
    FROM solicitacoes s
    JOIN usuarios u ON s.usuario_id = u.id
    JOIN setores st ON s.setor_id = st.id
    LEFT JOIN bps b ON s.bp_id = b.id
    WHERE st.gerente_id = '$gerente_id'
";


// 🔸 Aplica filtros se houver
if (!empty($filtro_setor)) {
    $sql .= " AND st.id = '$filtro_setor'";
}
if (!empty($filtro_usuario)) {
    $sql .= " AND u.id = '$filtro_usuario'";
}

$sql .= " ORDER BY s.data_solicitacao DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Solicitações | Controle Patrimonial</title>
    <link rel="stylesheet" href="../css/gerenciar_solicitacoes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="conteudo">
        <h1>Gerenciar Solicitações</h1>

        <!-- ===== FILTROS ===== -->
        <form method="GET" class="form-filtros">
            <div class="filtros-container">
                <div class="filtro">
                    <label for="setor">Filtrar por Setor:</label>
                    <select name="setor" id="setor" class="form-select">
                        <option value="">Todos os setores</option>
                        <?php while ($setor = $resultSetores->fetch_assoc()): ?>
                            <option value="<?= $setor['id'] ?>" <?= ($setor['id'] == $filtro_setor) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($setor['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="filtro">
                    <label for="usuario">Filtrar por Usuário:</label>
                    <select name="usuario" id="usuario" class="form-select">
                        <option value="">Todos os usuários</option>
                        <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                            <option value="<?= $usuario['id'] ?>" <?= ($usuario['id'] == $filtro_usuario) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($usuario['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn-filtrar">
                    <i class="fa-solid fa-filter"></i> Filtrar
                </button>
            </div>
        </form>

        <!-- ===== TABELA ===== -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Setor</th>
                    <th>Item</th>
                    <th>Tipo</th>
                    <th>Campo Alterado</th>
                    <th>Valor Atual</th>
                    <th>Novo Valor</th>
                    <th>Motivo</th>
                    <th>Status</th>
                    <th>Data Abertura</th>
                    <th>Data Aprovação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= htmlspecialchars($row['usuario_nome']); ?></td>
                            <td><?= htmlspecialchars($row['setor_nome']); ?></td>
                            <td><?= $row['nome_item'] ? htmlspecialchars($row['nome_item']) : '<em class="text-muted">Item excluído</em>'; ?></td>
                            <td><?= ucfirst($row['tipo']); ?></td>
                            <td><?= htmlspecialchars($row['campo_alterado'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['valor_atual'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['novo_valor'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['motivo']); ?></td>
                            <td class="<?= $row['status'] === 'pendente' ? 'text-warning' : ($row['status'] === 'aprovado' ? 'text-success' : 'text-danger'); ?>">
                                <?= ucfirst($row['status']); ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($row['data_solicitacao'])) ?></td>
                            <td>
                                <?= $row['data_aprovacao'] ? date('d/m/Y H:i', strtotime($row['data_aprovacao'])) : '<em class="text-muted">—</em>' ?>
                            </td>
                            <td class="acoes">
                                <?php if ($row['status'] === 'pendente'): ?>
                                    <form action="../actions/status_solicitacao.php" method="post" class="acoes">
                                        <input type="hidden" name="solicitacao_id" value="<?= $row['id']; ?>">
                                        <button name="acao" value="aprovar" type="submit" class="btn-aprovar" title="Aprovar">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button name="acao" value="recusar" type="submit" class="btn-recusar" title="Recusar">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                        
                                    </form>
                                <?php else: ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13" style="text-align:center; color:gray;">Nenhuma solicitação encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>