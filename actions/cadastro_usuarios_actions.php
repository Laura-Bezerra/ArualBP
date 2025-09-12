<?php
include_once('../includes/config.php');


if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $nivel_acesso = $_POST['nivel_acesso'];

    $sql = "INSERT INTO usuarios (nome, usuario, senha, nivel_acesso) 
            VALUES ('$nome', '$usuario', '$senha', '$nivel_acesso')";
    
    if ($conexao->query($sql)) {
        header('Location: ../pages/cadastro_usuario.php');
        exit;
    } else {
        echo "Erro ao cadastrar: " . $conexao->error;
    }
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $nivel_acesso = $_POST['nivel_acesso'];

    $sql = "UPDATE usuarios 
            SET nome='$nome', usuario='$usuario', nivel_acesso='$nivel_acesso' 
            WHERE id='$id'";
    
    if ($conexao->query($sql)) {
        header('Location: ../pages/cadastro_usuario.php');
        exit;
    } else {
        echo "Erro ao atualizar: " . $conexao->error;
    }
}


?>
