<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="../css/cadastro_user.css">
    <link rel="stylesheet" href="../css/modal_user.css">
</head>

<body>
    <div class="formulario-usuario">
        <div class="container">
            <form action="../actions/cadastro_usuarios_actions.php" method="POST">
                <fieldset>
                    <legend><b>Cadastro de Usuários</b></legend>

                    <div class="inputBox">
                        <input type="text" name="nome" id="nome" class="inputUser" required>
                        <label for="nome">Nome completo</label>
                    </div>

                    <div class="inputBox">
                        <input type="email" name="email" id="email" class="inputUser" required>
                        <label for="email">E-mail*</label>
                    </div>

                    <div class="linha-dupla">
                        <div class="inputBox metade">
                            <input type="text" name="usuario" id="usuario" class="inputUser" required>
                            <label for="usuario">Usuário*</label>
                        </div>

                        <div class="inputBox metade">
                            <input type="password" name="senha" id="senha" class="inputUser" required>
                            <label for="senha">Senha</label>
                        </div>
                    </div>

                    <div class="inputBox">
                        <label for="nivel_acesso">Nível de Acesso</label>
                        <br>
                        <select name="nivel_acesso" id="nivel_acesso">
                            <option value="usuario">Usuário Comum</option>
                            <option value="gerente">Gerente</option>
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
                            <th>Usuário</th>
                            <th>Nível de Acesso</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM usuarios ORDER BY id DESC";
                        $result2 = $conexao->query($sql);
                        while ($user_data = mysqli_fetch_assoc($result2)) {
                            $checked = $user_data['ativo'] ? 'checked' : '';
                            echo "<tr>";
                            echo "<td>{$user_data['id']}</td>";
                            echo "<td>{$user_data['nome']}</td>";
                            echo "<td>{$user_data['usuario']}</td>";
                            echo "<td>{$user_data['nivel_acesso']}</td>";
                            echo "<td>
                                <label class='switch'>
                                    <input type='checkbox' class='toggle-status' data-id='{$user_data['id']}' $checked>
                                    <span class='slider round'></span>
                                </label>
                              </td>";
                            echo "<td>
                                <button class='btn btn-success btn-sm editBtn'
                                        data-bs-toggle='modal' data-bs-target='#editModal'
                                        data-id='{$user_data['id']}'
                                        data-nome='{$user_data['nome']}'
                                        data-usuario='{$user_data['usuario']}'
                                        data-email='{$user_data['email']}'
                                        data-nivel='{$user_data['nivel_acesso']}'>
                                    Editar
                                </button>
                                <a href='../actions/delete_usuarios.php?id={$user_data['id']}' 
                                   onclick='return confirm(\"Tem certeza que deseja excluir?\")'
                                   class='btn btn-danger btn-sm'>Deletar</a>
                              </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include '../includes/modal_user.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/cadastro_usuario.js"></script>
    </div>
</body>

</html>