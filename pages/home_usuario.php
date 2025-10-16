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
if (isset($_POST['setor_selecionado'])) {
    $setorSelecionado = intval($_POST['setor_selecionado']);
    $sqlItensBP = "SELECT b.*, s.nome AS setor_nome
                   FROM bps b
                   JOIN setores s ON b.setor_id = s.id
                   WHERE b.setor_id = '$setorSelecionado'";
    $resultItensBP = $conexao->query($sqlItensBP);

    while ($row = $resultItensBP->fetch_assoc()) {
        $itensBP[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Home Usuário | GN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home_usuario.css">
    <?php include '../includes/navbar.php'; ?>
</head>
<body>

    <div class="container mt-5">
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

        <!-- Lista de BPs -->
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
                            <th>Descrição</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Quantidade</th>
                            <th>Data de Aquisição</th>
                            <th>Valor Total</th>
                            <th>Local</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itensBP as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td><?= htmlspecialchars($item['marca']) ?></td>
                            <td><?= htmlspecialchars($item['modelo']) ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td><?= date('d/m/Y', strtotime($item['data_aquisicao'])) ?></td>
                            <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($item['local']) ?></td>
                            <td>
                                <button type="button" 
                                        class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalSolicitacao"
                                        data-bp-id="<?= $item['id'] ?>">
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
</body>
</html>
