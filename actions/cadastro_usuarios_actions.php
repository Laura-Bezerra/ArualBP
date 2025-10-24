<?php
include_once('../includes/config.php');

if (isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $nivel_acesso = $_POST['nivel_acesso'];

    $sql_check = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt_check = $conexao->prepare($sql_check);
    $stmt_check->bind_param("s", $usuario);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "<script>
                alert('⚠️ O nome de usuário \"$usuario\" já está em uso. Escolha outro.');
                window.history.back();
              </script>";
        exit;
    }

    $sql = "INSERT INTO usuarios (nome, email, usuario, senha, nivel_acesso, ativo)
            VALUES (?, ?, ?, ?, ?, 1)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssss", $nome, $email, $usuario, $senha, $nivel_acesso);

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Usuário cadastrado com sucesso!');
                window.location.href = '../pages/cadastro_usuario.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('❌ Erro ao cadastrar usuário. Tente novamente.');
                window.history.back();
              </script>";
        exit;
    }
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'] ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;
    $nivel_acesso = $_POST['nivel_acesso'];

    try {
        // Verifica se já existe outro usuário com o mesmo nome de login
        $sql_check = "SELECT id FROM usuarios WHERE usuario = ? AND id != ?";
        $stmt_check = $conexao->prepare($sql_check);
        $stmt_check->bind_param("si", $usuario, $id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo "<script>
                    alert('⚠️ Já existe outro usuário com este nome de login.');
                    window.history.back();
                  </script>";
            exit;
        }

        // Atualiza o registro
        if ($senha) {
            $sql = "UPDATE usuarios SET nome=?, email=?, usuario=?, senha=?, nivel_acesso=? WHERE id=?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("sssssi", $nome, $email, $usuario, $senha, $nivel_acesso, $id);
        } else {
            $sql = "UPDATE usuarios SET nome=?, email=?, usuario=?, nivel_acesso=? WHERE id=?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssssi", $nome, $email, $usuario, $nivel_acesso, $id);
        }

        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ Usuário atualizado com sucesso!');
                    window.location.href = '../pages/cadastro_usuario.php';
                  </script>";
        } else {
            echo "<script>
                    alert('❌ Erro ao atualizar usuário. Tente novamente.');
                    window.history.back();
                  </script>";
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "<script>
                    alert('⚠️ Este nome de usuário já está sendo utilizado. Escolha outro.');
                    window.history.back();
                  </script>";
        } else {
            echo "<script>
                    alert('❌ Erro inesperado: " . addslashes($e->getMessage()) . "');
                    window.history.back();
                  </script>";
        }
    }

    exit;
}
?>
