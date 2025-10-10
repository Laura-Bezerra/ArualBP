<?php
include_once('../includes/config.php');

// Cadastrar novo usuário
if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $nivel_acesso = $_POST['nivel_acesso'];

    $sql = "INSERT INTO usuarios (nome, usuario, email, senha, nivel_acesso) 
            VALUES ('$nome', '$usuario', '$email', '$senha', '$nivel_acesso')";
    
    if ($conexao->query($sql)) {
        header('Location: ../pages/cadastro_usuario.php');
        exit;
    } else {
        echo "Erro ao cadastrar: " . $conexao->error;
    }
}

// Atualizar usuário existente
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $nivel_acesso = $_POST['nivel_acesso'];
    $senha = $_POST['senha'] ?? '';

    if (!empty($senha)) {
        // Atualiza tudo incluindo a nova senha
        $sql = "UPDATE usuarios 
                SET nome='$nome', usuario='$usuario', email='$email', senha='$senha', nivel_acesso='$nivel_acesso' 
                WHERE id='$id'";
    } else {
        // Atualiza os dados sem alterar a senha
        $sql = "UPDATE usuarios 
                SET nome='$nome', usuario='$usuario', email='$email', nivel_acesso='$nivel_acesso' 
                WHERE id='$id'";
    }

    if ($conexao->query($sql)) {
        header('Location: ../pages/cadastro_usuario.php');
        exit;
    } else {
        echo "Erro ao atualizar: " . $conexao->error;
    }
}
