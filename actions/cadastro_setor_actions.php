<?php
include_once('../includes/config.php');


if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $usuario_id = $_POST['usuario_id'];

    $sql = "INSERT INTO setores (nome, usuario_id) VALUES ('$nome', '$usuario_id')";
    if (mysqli_query($conexao, $sql)) {
        header("Location: ../pages/cadastro_setor.php");
        exit;
    } else {
        echo "Erro: " . mysqli_error($conexao);
    }
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $usuario_id = $_POST['usuario_id'];

    $sql = "UPDATE setores SET nome='$nome', usuario_id='$usuario_id' WHERE id='$id'";
    if (mysqli_query($conexao, $sql)) {
        header("Location: ../pages/cadastro_setor.php");
        exit;
    } else {
        echo "Erro: " . mysqli_error($conexao);
    }
}
?>
