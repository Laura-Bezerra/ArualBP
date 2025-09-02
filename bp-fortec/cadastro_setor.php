<?php
// Incluindo a conexão com o banco de dados
include_once('config.php');

// Verificando se o formulário foi submetido
if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $usuario_id = $_POST['usuario_id'];  // Corrigindo para pegar o campo correto do formulário

    // Inserindo os dados na tabela 'setores'
    $result = mysqli_query($conexao, "INSERT INTO setores(nome, usuario_id) 
    VALUES ('$nome', '$usuario_id')");

    // Redirecionando para a mesma página após o envio
    if ($result) {
        header('Location: cadastro_setor.php');
        exit;
    } else {
        echo "Erro ao inserir os dados: " . mysqli_error($conexao);
    }
}

// Consultando os dados da tabela 'setores' e 'usuarios'
$sqlSetores = "SELECT * FROM setores ORDER BY id DESC";
$resultSetores = $conexao->query($sqlSetores);

$sqlUsuarios = "SELECT * FROM usuarios ORDER BY nome ASC"; // Consultando usuários para vincular ao setor
$resultUsuarios = $conexao->query($sqlUsuarios);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Setores</title>
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
            padding: 30px;
            width: 90%;
            max-width: 900px;
            margin: auto;
            overflow: hidden;
        }
        form {
            margin-bottom: 20px;
        }
        fieldset {
            border: none;
            padding: 0;
        }
        legend {
            font-size: 1.8em;
            margin-bottom: 15px;
            text-align: center;
            color: #e0e0e0;
        }
        .inputBox {
            position: relative;
            margin-bottom: 20px;
        }
        .inputUser {
            width: 100%;
            padding: 12px;
            border: 1px solid #34495e;
            border-radius: 8px;
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
            left: 15px;
            top: 15px;
            transform: translateY(-50%);
            color: #bdc3c7;
            pointer-events: none;
            transition: all 0.3s;
        }
        .inputUser:focus + label,
        .inputUser:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 0.9em;
            color: #1abc9c;
        }
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #34495e;
            border-radius: 8px;
            background-color: #34495e;
            color: #f0f0f0;
            outline: none;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #1abc9c;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #16a085;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            padding: 12px;
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
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>    
    <div class="container">
        <form action="cadastro_setor.php" method="POST">
            <fieldset>
                <legend><b>Cadastro de Setores</b></legend>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                    <label for="nome">Nome do setor</label>
                </div>
                <select name="usuario_id" id="usuario_id" required>
                    <option value="">Selecione um usuário</option>
                    <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                        <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nome']; ?></option>
                    <?php endwhile; ?>
                </select><br>
                <input type="submit" name="submit" id="submit" value="Cadastrar">
            </fieldset>
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Setor</th>
                        <th>Responsável</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($setor = mysqli_fetch_assoc($resultSetores)) {
                        // Consultando o nome do responsável (usuário) associado ao setor
                        $usuarioId = $setor['usuario_id'];
                        $sqlUsuarioResponsavel = "SELECT nome FROM usuarios WHERE id = '$usuarioId'";
                        $resultUsuarioResponsavel = mysqli_query($conexao, $sqlUsuarioResponsavel);
                        $usuarioResponsavel = mysqli_fetch_assoc($resultUsuarioResponsavel);

                        echo "<tr>";
                        echo "<td>".$setor['id']."</td>";
                        echo "<td>".$setor['nome']."</td>";
                        echo "<td>".$usuarioResponsavel['nome']."</td>";  // Exibindo nome do responsável
                        echo "<td>
                            <a href='edit_setores.php?id=".$setor['id']."'>Editar</a> | 
                            <a href='delete_setores.php?id=".$setor['id']."'>Deletar</a>
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
