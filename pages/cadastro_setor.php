<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';

$sqlSetores = "SELECT * FROM setores ORDER BY id DESC";
$resultSetores = $conexao->query($sqlSetores);

$sqlUsuarios = "SELECT * FROM usuarios ORDER BY nome ASC"; 
$resultUsuarios = $conexao->query($sqlUsuarios);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="../css/cadastro_user.css">
</head>
<body>
<div class="formulario-usuario">
    <div class="container">
        <form action="../actions/cadastro_setor_actions.php" method="POST">
            <fieldset>
                <legend><b>Cadastro de Setores</b></legend>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                    <label for="nome">Nome do Setor</label>
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

        <!-- Tabela de Setores -->
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
                    <?php while ($setor = mysqli_fetch_assoc($resultSetores)): ?>
                        <?php
                        $usuarioId = $setor['usuario_id'];
                        $sqlUsuarioResponsavel = "SELECT nome FROM usuarios WHERE id = '$usuarioId'";
                        $resultUsuarioResponsavel = mysqli_query($conexao, $sqlUsuarioResponsavel);
                        $usuarioResponsavel = mysqli_fetch_assoc($resultUsuarioResponsavel);
                        ?>
                        <tr>
                            <td><?= $setor['id'] ?></td>
                            <td><?= $setor['nome'] ?></td>
                            <td><?= $usuarioResponsavel['nome'] ?? 'N/A' ?></td>
                            <td>
                                <button class="btn-editar btn-success btn-sm editSetorBtn"                                
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSetorModal"
                                        data-id="<?= $setor['id'] ?>"
                                        data-nome="<?= $setor['nome'] ?>"
                                        data-usuario="<?= $setor['usuario_id'] ?>">
                                    Editar
                                </button>

                                
                                <a href="../actions/delete_setor.php?id=<?= $setor['id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Tem certeza que deseja excluir este setor?')">
                                   Deletar
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

     <?php include '../includes/modal_setor.php'; ?>
    <script src="../js/cadastro_setor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>