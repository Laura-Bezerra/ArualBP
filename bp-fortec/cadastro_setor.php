<?php
// Incluindo a conexão com o banco de dados
include_once('config.php');

// Verificando se o formulário foi submetido
if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $usuario_id = $_POST['usuario_id']; 

    // Inserindo os dados na tabela 'setores'
    $result = mysqli_query($conexao, "INSERT INTO setores(nome, usuario_id) VALUES ('$nome', '$usuario_id')");

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

$sqlUsuarios = "SELECT * FROM usuarios ORDER BY nome ASC"; 
$resultUsuarios = $conexao->query($sqlUsuarios);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Setores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #d9d9d9;
            color: #441281;
            display: flex;
            justify-content: center;
            margin-top: 80px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 90%;
            max-width: 900px;
            margin: auto;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #441281;
            z-index: 1000;
            border-radius: 0 0 15px 15px;
        }
        .navbar .navbar-brand, .navbar .navbar-nav .nav-link {
            color: #f5ad00;
            border-radius: 5px;
            padding: 10px 30px;
            transition: background-color 0.3s ease;
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
        .navbar-toggler {
            background-color: #f5ad00;
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
            color: #441281;
        }
        .inputBox {
            position: relative;
            margin-bottom: 20px;
        }
        .inputUser, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #915ad3;
            border-radius: 8px;
            background-color: #f5f5f5;
            color: #441281;
            outline: none;
        }
        .inputUser:focus, select:focus {
            border-color: #f5ad00;
        }
        label {
            position: absolute;
            left: 15px;
            top: 15px;
            color: #915ad3;
            transition: all 0.3s;
        }
        .inputUser:focus + label,
        .inputUser:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 0.9em;
            color: #f5ad00;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #f5ad00;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #e59c00;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            padding: 12px;
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
                <ul class="navbar-nav mx-auto">
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

    <div class="container">
        <form action="cadastro_setor.php" method="POST">
            <fieldset>
                <legend><b>Cadastro de Setores</b></legend>
                <div class="inputBox">
                <label for="nome">Nome do setor</label><br><br>
                    <input type="text" name="nome" id="nome" class="inputUser" required>
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
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($setor = mysqli_fetch_assoc($resultSetores)) {
                        $usuarioId = $setor['usuario_id'];
                        $sqlUsuarioResponsavel = "SELECT nome FROM usuarios WHERE id = '$usuarioId'";
                        $resultUsuarioResponsavel = mysqli_query($conexao, $sqlUsuarioResponsavel);
                        $usuarioResponsavel = mysqli_fetch_assoc($resultUsuarioResponsavel);

                        echo "<tr>";
                        echo "<td>".$setor['id']."</td>";
                        echo "<td>".$setor['nome']."</td>";
                        echo "<td>".$usuarioResponsavel['nome']."</td>";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
