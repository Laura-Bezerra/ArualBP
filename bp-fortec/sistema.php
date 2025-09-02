<?php
session_start();
include_once('config.php');

// Verifica se o usuário está logado e se o nível de acesso é admin
if ((!isset($_SESSION['email']) || !isset($_SESSION['senha'])) || ($_SESSION['nivel_acesso'] !== 'admin')) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php'); // Redireciona para a página de login se não for admin
    exit(); // Para a execução do script
}

// Armazena o e-mail do usuário logado
$logado = $_SESSION['email'];

// Verifica se há uma pesquisa
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM usuarios WHERE id LIKE '%$data%' OR nome LIKE '%$data%' OR email LIKE '%$data%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM usuarios ORDER BY id DESC";
}
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>IC 2024 | BSI 6</title>
    <style>
        body {
            background: #441281; /* Fundo claro */
            color: #6e04b5; /* Cor principal */
        }
        /* Menu fixo no topo */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #441281; /* Cor roxa escura */
            z-index: 1000;
            border-radius: 0 0 15px 15px; /* Borda arredondada no fundo */
            border-bottom: none; /* Remover borda inferior */
        }
        .navbar .navbar-brand {
            color: #f5ad00; /* Cor amarela */
        }
        .navbar .navbar-nav .nav-link {
            color: #f5ad00;
            transition: background-color 0.3s ease;
            border-radius: 5px; /* Borda arredondada nos links do menu */
            padding: 10px 30px; /* Espaçamento dentro dos links */
        }
        .navbar .navbar-nav .nav-link:hover {
            background-color: #f5ad00;
            color: white; /* Manter o texto branco ao passar o mouse */
        }
        .btn-danger {
            background-color: #e74c3c; /* Botão sair vermelho */
            border-radius: 5px; /* Borda arredondada */
            padding: 10px 30px;
        }
        /* Caixa de pesquisa */
        .box-search {
            display: flex;
            justify-content: center;
            gap: .1%;
            margin-top: 80px; /* Deixa espaço para o menu fixo */
        }
        .form-control::placeholder {
            color: #441281; /* Cor mais clara para o texto do placeholder */
        }
        /* Centralizando e distribuindo os botões */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px; /* Espaçamento entre os botões */
            margin-top: 100px; /* Espaço superior para não sobrepor o menu fixo */
        }
        .btn-custom {
            background-color: #915ad3; /* Cor roxa para o botão */
            color: white;
            border-radius: 5px;
            padding: 20px 40px;
            text-align: center;
            font-size: 16px;
            width: 200px;
        }
        .btn-custom:hover {
            background-color: #6203a6;
                        color: white;

        }
        /* Estilos para a tabela */
        table {
            margin-top: 20px;
            width: 100%;
            margin-left: 50;
            margin-right: 60;
            border: 20px solid #441281;
            border-radius: 100px;
        }
        .table th {
            background-color: #441281;
            color: white;
        }
        .table td, .table th {
            border: 1px solid #6203a6;
        }
        .table tbody tr:nth-child(even) {
            background-color: #d9d9d9;
        }

        /* Adicionando margem superior para o título */
        .welcome-title {
            margin-top: 120px; /* Garantir que o título fique abaixo da navbar */
            text-align: center;
            color: white
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CONTROLE BP's</a>
            <!-- Botão Sair na navbar -->
            <a href="sair.php" class="btn btn-danger">Sair</a>
        </div>
    </nav>

    <!-- Exibindo o Bem-vindo acima dos botões -->
    <br>
    <h1 class="welcome-title">Bem-vindo <u><?php echo $logado; ?></u></h1>
    <br>

    <!-- Centralizando os botões -->
    <div class="button-container">
        <a href="cadastro_bp.php" class="btn btn-custom">Cadastro BP</a>
        <a href="cadastro_usuario.php" class="btn btn-custom">Cadastro Usuário</a>
        <a href="cadastro_setor.php" class="btn btn-custom">Cadastro Setor</a>
        <a href="solicitacoes_alteracoes.php" class="btn btn-custom">Solicitação de Alteração</a>
        <a href="relatorios.php" class="btn btn-custom">Relatórios</a>
    </div>

    <br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
