<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';

// Verifica se o usuário é realmente 'usuario'
if (!isset($_SESSION['usuario']) || $_SESSION['nivel_acesso'] !== 'usuario') {
    header('Location: ../login.php');
    exit();
}

$logado = $_SESSION['usuario'];
$itensBP = [];

// Obter ID do usuário logado
$sqlUsuario = "SELECT id FROM usuarios WHERE usuario = '$logado'";
$resultUsuario = $conexao->query($sqlUsuario);
$idUsuario = $resultUsuario->fetch_assoc()['id'];

// 🔹 Buscar todos os setores que o usuário faz parte (pela tabela setor_usuarios)
$sqlSetores = "
    SELECT s.id, s.nome
    FROM setores s
    JOIN setor_usuarios su ON su.setor_id = s.id
    WHERE su.usuario_id = '$idUsuario'
";
$resultSetores = $conexao->query($sqlSetores);

// 🔹 Buscar BPs do setor selecionado
if (isset($_POST['setor_selecionado']) || isset($_GET['setor_selecionado'])) {
    $setorSelecionado = isset($_POST['setor_selecionado'])
        ? intval($_POST['setor_selecionado'])
        : intval($_GET['setor_selecionado']);

    $sqlItensBP = "
        SELECT 
            b.*, 
            s.nome AS setor_nome,
            (SELECT COUNT(*) FROM solicitacoes sol WHERE sol.bp_id = b.id) AS total_solicitacoes
        FROM bps b
        JOIN setores s ON b.setor_id = s.id
        WHERE b.setor_id = $setorSelecionado
    ";
    $resultItensBP = $conexao->query($sqlItensBP);

    while ($row = $resultItensBP->fetch_assoc()) {
        $itensBP[] = $row;
    }
} elseif (isset($_GET['setor_selecionado'])) {
    $setorSelecionado = intval($_GET['setor_selecionado']);
}



?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Home Usuário | GN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home_usuario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php include '../includes/navbar.php'; ?>
</head>

<body>

    <div class="container mt-5">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success text-center"
                style="max-width: 800px; margin: 0 auto 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                <i class="fa-solid fa-circle-check"></i>
                Solicitação enviada com sucesso!
            </div>
        <?php endif; ?>
        <h2 class="text-center mb-4">Bem-vindo, <u><?= htmlspecialchars($logado); ?></u></h2>

        <!-- Selecionar setor -->
        <div class="card p-4 shadow-sm mb-4">
            <form method="post" class="row g-3">
                <div class="col-md-8">
                    <label for="setor_selecionado" class="form-label"><strong>Selecione o Setor:</strong></label>
                    <select name="setor_selecionado" id="setor_selecionado" class="form-select" required>
                        <option value="">Escolha um setor...</option>
                        <?php while ($setor = $resultSetores->fetch_assoc()): ?>
                            <option value="<?= $setor['id'] ?>"
                                <?= isset($setorSelecionado) && $setorSelecionado == $setor['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($setor['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-eye"></i> Ver BPs
                    </button>
                </div>
            </form>
        </div>

        <!-- ==================== LISTA DE BPs ==================== -->
        <?php if (!empty($itensBP)): ?>
            <div class="card shadow-sm p-3">
                <h4 class="mb-3 text-table">
                    Bens Patrimoniais do Setor: <?= htmlspecialchars($itensBP[0]['setor_nome']); ?>
                </h4>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-home-user">
                            <tr>
                                <th>ID</th>
                                <th>Nome do Item</th>
                                <th>Descrição</th>
                                <th>Marca</th>
                                <th>Quantidade</th>
                                <th>Data de Aquisição</th>
                                <th>Valor Total</th>
                                <th>Local</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itensBP as $item): ?>
                                <?php
                                // 🔹 Buscar solicitações pendentes para esse item
                                $sqlSolic = "
                                SELECT id, tipo 
                                FROM solicitacoes 
                                WHERE bp_id = {$item['id']} AND status = 'pendente'
                                ORDER BY data_solicitacao DESC
                            ";
                                $resultSolic = $conexao->query($sqlSolic);
                                $popoverContent = '';
                                while ($sol = $resultSolic->fetch_assoc()) {
                                    $popoverContent .= "ID {$sol['id']} - " . ucfirst($sol['tipo']) . "<br>";
                                }
                                ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><?= htmlspecialchars($item['nome_item']) ?>

                                        <!-- Ícone de alerta se houver solicitações pendentes -->
                                        <?php if (!empty($popoverContent)): ?>
                                            <button
                                                class="btn btn-solic-info"
                                                data-bs-toggle="popover"
                                                data-bs-trigger="focus"
                                                title="Solicitações Pendentes"
                                                data-bs-html="true"
                                                data-bs-content="<?= htmlspecialchars($popoverContent) ?>">
                                                <i class="fa-solid fa-exclamation"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= htmlspecialchars($item['descricao']) ?></td>
                                    <td><?= htmlspecialchars($item['marca']) ?></td>
                                    <td><?= $item['quantidade'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['data_aquisicao'])) ?></td>
                                    <td>R$ <?= number_format($item['custo_total'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($item['local']) ?></td>
                                    <td class="text-center">
                                        <!-- Botão para solicitar modificação -->
                                        <button type="button"
                                            class="btn btn-warning btn-sm me-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalSolicitacao"
                                            data-bp-id="<?= $item['id'] ?>"
                                            data-nome_item="<?= htmlspecialchars($item['nome_item']) ?>"
                                            data-descricao="<?= htmlspecialchars($item['descricao']) ?>"
                                            data-marca="<?= htmlspecialchars($item['marca']) ?>"
                                            data-modelo="<?= htmlspecialchars($item['modelo']) ?>"
                                            data-quantidade="<?= htmlspecialchars($item['quantidade']) ?>"
                                            data-local="<?= htmlspecialchars($item['local']) ?>"
                                            data-fornecedor="<?= htmlspecialchars($item['fornecedor']) ?>"
                                            data-custo_unitario="<?= htmlspecialchars($item['custo_unitario']) ?>"
                                            data-custo_total="<?= htmlspecialchars($item['custo_total']) ?>"
                                            data-condicao_aquisicao="<?= htmlspecialchars($item['condicao_aquisicao']) ?>"
                                            data-estado_item="<?= htmlspecialchars($item['estado_item']) ?>"
                                            data-observacoes="<?= htmlspecialchars($item['observacoes']) ?>">
                                            Solicitar Modificação
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php elseif (isset($setorSelecionado)): ?>
            <div class="alert alert-info mt-4 text-center">
                Nenhum BP cadastrado para este setor.
            </div>
        <?php endif; ?>

    </div>

    <?php include '../includes/modal_solicitacao.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/home_usuario.js"></script>
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.transition = 'opacity 0.6s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 600);
            }
        }, 3500);
    </script>

</body>

</html>