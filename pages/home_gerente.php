<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php'; 

if (!isset($_SESSION['usuario']) || $_SESSION['nivel_acesso'] !== 'gerente') {
    session_unset(); // limpa tudo
    header('Location: login.php');
    exit();
}


$logado = $_SESSION['usuario'];


if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM usuarios WHERE id LIKE '%$data%' OR nome LIKE '%$data%' OR usuario LIKE '%$data%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM usuarios ORDER BY id DESC";
}
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/home-admin.css">
    <?php include '../includes/navbar.php'; ?>

</head>
<body>

    <h1 class="welcome-title">Bem-vindo <u><?php echo $logado; ?></u></h1>
    <br>
    <div class="button-container">
        <a href="cadastro_bp.php" class="btn btn-custom">Cadastro BP</a>
        <a href="cadastro_usuario.php" class="btn btn-custom">Cadastro Usuário</a>
        <a href="cadastro_setor.php" class="btn btn-custom">Cadastro Setor</a>
        <a href="gerenciar_solicitacoes.php" class="btn btn-custom">Gerenciar Solicitações</a>
        <a href="relatorios.php" class="btn btn-custom">Relatórios</a>
    </div>

    <br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php include '../includes/footer.php'; ?>

