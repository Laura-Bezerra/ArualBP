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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <a href="sistema.php">VOLTAR</a>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>        
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #2c3e50;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            padding: 20px;
            width: 90%;
            max-width: 800px;
            margin: auto;
        }
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
            color: #e0e0e0;
        }
        .inputBox {
            position: relative;
            margin-bottom: 15px;
        }
        .inputUser {
            width: 100%;
            padding: 10px;
            border: 1px solid #34495e;
            border-radius: 5px;
            background-color: #34495e;
            color: #f0f0f0;
            outline: none;
            transition: all 0.3s;
        }
        .inputUser:focus {
            border-color: #1abc9c;
        }
        label {
            position: absolute;
            
            left: 10px;
            transform: translateY(-50%);
            color: #bdc3c7;
            pointer-events: none;
            transition: all 0.3s;
        }
        .inputUser:focus + label,
        .inputUser:not(:placeholder-shown) + label {
            top: -10px;
            left: 5px;
            font-size: 0.9em;
            color: #1abc9c;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #34495e;
            border-radius: 5px;
            background-color: #34495e;
            color: #f0f0f0;
            outline: none;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #1abc9c;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #16a085;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #34495e;
        }
        th {
            background-color: #1abc9c;
            color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #34495e;
        }
        a {
            color: #1abc9c;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
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
                    <label for="email">Email</label>
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
                            <a href='delete_usuarios.php?id=".$user_data['id']."'>Deletar</a>
                        </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
