<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php'; 
include '../includes/navbar.php';

if (!isset($_SESSION['id']) || $_SESSION['nivel_acesso'] !== 'usuario') {
    header('Location: ../login.php');
    exit();
}

$usuario_id = $_SESSION['id'];

$sql = "SELECT s.id, s.descricao, s.data_solicitacao, s.status,
               b.descricao AS bp_descricao
        FROM solicitacoes s
        JOIN bps b ON s.bp_id = b.id
        WHERE s.setor_id IN (SELECT id FROM setores WHERE usuario_id = '$usuario_id')";

$result = $conexao->query($sql);
$solicitacoes = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $solicitacoes[] = $row;
    }
}
?>

<head>
<link rel="stylesheet" href="../css/solicitacoes.css">

</head>
<div class="container mt-5">
    <h1 class="text-center">Solicitações de Alteração</h1>

    <?php if (!empty($solicitacoes)): ?>
        <table class="solicitacoes-table table table-hover mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item da BP</th>
                    <th>Descrição</th>
                    <th>Data Solicitação</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitacoes as $solicitacao): ?>
                    <tr>
                        <td><?= $solicitacao['id']; ?></td>
                        <td><?= $solicitacao['bp_descricao']; ?></td>
                        <td><?= $solicitacao['descricao']; ?></td>
                        <td><?= $solicitacao['data_solicitacao']; ?></td>
                        <td><?= $solicitacao['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-danger mt-4">Nenhuma solicitação encontrada.</p>
    <?php endif; ?>

    <a href="home_usuario.php" class="btn btn-primary mt-3 d-block mx-auto" style="width:200px;">Voltar</a>
</div>