<?php
// Incluindo a conexão com o banco de dados
include_once('config.php');

// Verificando se o formulário foi submetido
if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nivel_acesso = $_POST['nivel_acesso'];

    // Inserindo os dados na tabela 'usuarios'
    $result = mysqli_query($conexao, "INSERT INTO usuarios(nome, email, senha, nivel_acesso) 
    VALUES ('$nome', '$email', '$senha', '$nivel_acesso')");

    // Redirecionando para a mesma página após o envio
    if ($result) {
        header('Location: cadastro_usuario.php');
        exit;
    } else {
        echo "Erro ao inserir os dados: " . mysqli_error($conexao);
    }
}

// Consultando os dados da tabela 'usuarios'
$sql = "SELECT * FROM usuarios ORDER BY id DESC";
$result2 = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>        
        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #441281;
            z-index: 1000;
            border-radius: 0 0 15px 15px;
        }
        .navbar .navbar-brand {
            color: #f5ad00;
        }
        .navbar .navbar-nav .nav-link {
            color: #f5ad00;
            transition: background-color 0.3s ease;
            border-radius: 5px;
            padding: 10px 30px;
        }
        .navbar .navbar-nav .nav-link:hover {
            background-color: #f5ad00;
            color: #441281;
        }
        .btn-danger {
            background-color: #e74c3c;
            border-radius: 5px;
            padding: 10px 30px;
        }
        .navbar .navbar-nav .nav-link {
            border-radius: 15px;
        }
        .container-fluid {
            background: #441281;
        }
        .navbar-toggler {
            background-color: #f5ad00;
        }

        /* Estilo do contêiner principal */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #d9d9d9;
            color: #441281;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 80px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 90%;
            max-width: 800px;
            margin: 20px;
        }

        /* Estilos dos elementos do formulário */
        form {
            margin-bottom: 20px;
        }
        fieldset {
            border: none;
            padding: 0;
        }
        legend {
            font-size: 1.5em;
            margin-bottom: 10px;
            text-align: center;
            color: #441281;
        }
        .inputBox {
            position: relative;
            margin-bottom: 15px;
        }
        .inputUser {
            width: 100%;
            padding: 10px;
            border: 1px solid #915ad3;
            border-radius: 5px;
            background-color: #f5f5f5;
            color: #441281;
            outline: none;
            transition: all 0.3s;
        }
        .inputUser:focus {
            border-color: #f5ad00;
        }
        label {
            position: absolute;
            top: 0;
            left: 10px;
            transform: translateY(-50%);
            color: #915ad3;
            pointer-events: none;
            transition: all 0.3s;
        }
        .inputUser:focus + label,
        .inputUser:not(:placeholder-shown) + label {
            top: -10px;
            left: 5px;
            font-size: 0.9em;
            color: #f5ad00;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #915ad3;
            border-radius: 5px;
            background-color: #f5f5f5;
            color: #441281;
            outline: none;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #f5ad00;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #e59c00;
        }

        /* Estilos da tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #915ad3;
        }
        th {
            background-color: #915ad3;
            color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        a {
            color: #f5ad00;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CONTROLE BP's</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto"> <!-- Centralizar os botões do menu -->
                <li class="nav-item">
                        <a class="nav-link" href="sistema.php">Início</a>
                    </li>                    
                <li class="nav-item">    
                <li class="nav-item">
                        <a class="nav-link" href="cadastro_bp.php">Cadastro BP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro_usuario.php">Cadastro Usuário</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro_setor.php">Cadastro Setor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="solicitacoes_alteracoes.php">Solicitação de Alteração</a>
                    </li>
                </ul>
                <a href="sair.php" class="btn btn-danger me-5">Sair</a>
            </div>
        </div>
    </nav>

    <!-- Formulário e tabela -->
    <div class="container">
        <form action="cadastro_usuario.php" method="POST">
            <fieldset>
                <legend><b>Cadastro de Usuários</b></legend>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                    <label for="nome">Nome completo</label>
                </div>
                <div class="inputBox">
                    <input type="text" name="email" id="email" class="inputUser" required>
                    <label for="email">Nome de Usuário</label>
                </div>
                <div class="inputBox">
                    <input type="password" name="senha" id="senha" class="inputUser" required>
                    <label for="senha">Senha</label>
                </div>
                
                <div class="inputBox">                    
                <label for="nivel_acesso">Nível de Acesso</label>                        
                <br>
                    <select name="nivel_acesso" id="nivel_acesso">
                        <option value="usuario">Usuário Comum</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <input type="submit" name="submit" id="submit" value="Cadastrar">
            </fieldset>
        </form>

        <!-- Tabela de usuários -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nível de Acesso</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    while ($user_data = mysqli_fetch_assoc($result2)) {
        echo "<tr>";
        echo "<td>".$user_data['id']."</td>";
        echo "<td>".$user_data['nome']."</td>";
        echo "<td>".$user_data['email']."</td>";
        echo "<td>".$user_data['nivel_acesso']."</td>";
        echo "<td>
            <a href='edit_usuarios.php?id=".$user_data['id']."'>Editar</a> | 
            <a href='delete_usuarios.php?id=".$user_data['id']."' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Deletar</a>
        </td>";
        echo "</tr>";
    }
    ?>
</tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>