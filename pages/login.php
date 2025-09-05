<?php include '../includes/header.php'; ?>

<head>

    <link rel="stylesheet" href="../css/estilo.css">
    
</head>

<body>
    <a href=".." class="backButton">Voltar</a>
    <div class="login-container">
        <h1>Login</h1>
        <form action="../actions/testeLogin.php" method="POST">
            <input type="text" name="usuario" placeholder="Nome de Usuário">
            <input type="password" name="senha" placeholder="Senha">
            <input class="inputSubmit" type="submit" name="submit" value="Entrar">
        </form>
    </div>


    <?php include '../includes/footer.php'; ?>
