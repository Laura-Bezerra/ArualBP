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

// Obter setores do usuário
$sqlSetores = "SELECT id, nome FROM setores WHERE usuario_id = '$idUsuario'";
$resultSetores = $conexao->query($sqlSetores);

// Obter itens BP do setor selecionado
if (isset($_POST['setor_selecionado'])) {
    $setorSelecionado = $_POST['setor_selecionado'];
    $sqlItensBP = "SELECT * FROM bps WHERE setor_id = '$setorSelecionado'";
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

    <h1>Bem-vindo <u><?= htmlspecialchars($logado); ?></u></h1>

    <div class="form-container">
        <form method="post">
            <label for="setor_selecionado">Selecione o setor:</label>
            <select name="setor_selecionado" id="setor_selecionado" class="form-select">
                <?php while ($setor = $resultSetores->fetch_assoc()): ?>
                    <option value="<?= $setor['id'] ?>"><?= $setor['nome'] ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary">Ver BP</button>
        </form>
    </div>

    <?php if (!empty($itensBP)): ?>
        <div class="container mt-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Setor</th>
                        <th>Quantidade</th>
                        <th>Descrição</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Data Aquisição</th>
                        <th>Valor Total</th>
                        <th>Especificações</th>
                        <th>Local</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itensBP as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= $item['setor_id'] ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td><?= $item['descricao'] ?></td>
                            <td><?= $item['marca'] ?></td>
                            <td><?= $item['modelo'] ?></td>
                            <td><?= $item['data_aquisicao'] ?></td>
                            <td><?= $item['valor_total'] ?></td>
                            <td><?= $item['especificacoes_tecnicas'] ?></td>
                            <td><?= $item['local'] ?></td>
                            <td>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalSolicitacao" data-bp-id="<?= $item['id'] ?>">Solicitar Modificação</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php include '../includes/modal_solicitacao.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/home_usuario.js"></script>
</body>
</html>
