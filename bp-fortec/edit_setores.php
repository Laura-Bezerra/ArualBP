<?php
include_once('config.php');

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sqlSelect = "SELECT * FROM setores WHERE id=$id";
    $result = $conexao->query($sqlSelect);
    if ($result->num_rows > 0) {
        $user_data = mysqli_fetch_assoc($result);
        $nome = $user_data['nome'];
        $usuario_id = $user_data['usuario_id']; // Responsável atual
    } else {
        header('Location: cadastro_setores.php');
        exit;
    }
} else {
    header('Location: cadastro_setores.php');
    exit;
}

// Consultar todos os usuários para exibir como opções
$sqlUsuarios = "SELECT * FROM usuarios ORDER BY nome ASC";
$resultUsuarios = $conexao->query($sqlUsuarios);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Setor</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #915ad3;
        }
        .box {
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #441281;
            padding: 15px;
            border-radius: 15px;
            width: 20%;
        }
        fieldset {
            border: 3px solid #f5ad00;
        }
        legend {
            border: 1px solid #f5ad00;
            padding: 10px;
            text-align: center;
            background-color: #f5ad00;
            border-radius: 8px;
        }
        .inputBox {
            position: relative;
        }
        .inputUser {
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: white;
            font-size: 15px;
            width: 100%;
            letter-spacing: 2px;
        }
        .labelInput {
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .5s;
        }
        .inputUser:focus ~ .labelInput,
        .inputUser:valid ~ .labelInput {
            top: -20px;
            font-size: 12px;
            color: #f5ad00;
        }
        #data_nascimento {
            border: none;
            padding: 8px;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
        }
        #submit {
            background-color: #f5ad00;
            width: 100%;
            border: none;
            padding: 15px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
        }
        #submit:hover {
            background-color: #915ad3;
        }
        .backButton {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #f5ad00;
            color: #441281;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .backButton:hover {
            background-color: #d9d9d9;
        }

    </style>
</head>
<body>
    <a href="cadastro_setor.php" class="backButton">Voltar</a>
    <div class="box">
        <form action="saveEdit_setores.php" method="POST">
            <fieldset>
                <legend><b>Editar Setor</b></legend>
                <br>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" value="<?php echo $nome; ?>" required>                    
                </div>
                <br>
                <label for="usuario_id">Responsável</label>
                <select name="usuario_id" id="usuario_id" required>                    
                    <option value="">Selecione um usuário</option>
                    <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                        <option value="<?php echo $usuario['id']; ?>" <?php echo ($usuario['id'] == $usuario_id) ? 'selected' : ''; ?>>
                            <?php echo $usuario['nome']; ?>
                        </option>
                    <?php endwhile; ?>
                </select><br>
                <br>    

                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" name="update" id="submit" value="Salvar Alterações">
            </fieldset>
        </form>
    </div>
</body>
</html>
