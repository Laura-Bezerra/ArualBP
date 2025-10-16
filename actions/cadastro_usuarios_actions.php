<?php
include_once('../includes/config.php');

// ========== INSERIR NOVO USUÁRIO ==========
if (isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $nivel_acesso = $_POST['nivel_acesso'];

    // Criptografa a senha antes de salvar
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, usuario, email, senha, nivel_acesso) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssss", $nome, $usuario, $email, $senha_hash, $nivel_acesso);

    if ($stmt->execute()) {
        header('Location: ../pages/cadastro_usuario.php?status=success');
        exit;
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }
}

// ========== ATUALIZAR USUÁRIO EXISTENTE ==========
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $nivel_acesso = $_POST['nivel_acesso'];
    $senha = $_POST['senha'] ?? '';

    if (!empty($senha)) {
        // Atualiza com nova senha (criptografada)
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios 
                SET nome=?, usuario=?, email=?, senha=?, nivel_acesso=? 
                WHERE id=?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssi", $nome, $usuario, $email, $senha_hash, $nivel_acesso, $id);
    } else {
        // Atualiza sem alterar senha
        $sql = "UPDATE usuarios 
                SET nome=?, usuario=?, email=?, nivel_acesso=? 
                WHERE id=?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $usuario, $email, $nivel_acesso, $id);
    }

    if ($stmt->execute()) {
        header('Location: ../pages/cadastro_usuario.php?status=updated');
        exit;
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
}
?>
