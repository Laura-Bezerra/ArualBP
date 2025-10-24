<?php
include_once('../includes/config.php');
session_start();

if (!isset($_SESSION['id']) || !in_array($_SESSION['nivel_acesso'], ['gerente', 'admin'])) {
    header('Location: login.php');
    exit();
}

// Inserção
if (isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);

    $check = $conexao->prepare("SELECT COUNT(*) AS total FROM categorias WHERE nome = ?");
    $check->bind_param("s", $nome);
    $check->execute();
    $exists = $check->get_result()->fetch_assoc();

    if ($exists['total'] > 0) {
        echo "<script>
                alert('Já existe uma categoria com este nome!');
                window.location.href = '../pages/cadastro_categoria.php';
              </script>";
        exit;
    }

    $sql = "INSERT INTO categorias (nome) VALUES (?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $nome);
    $stmt->execute();

    header('Location: ../pages/cadastro_categoria.php');
    exit();
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);

    $check = $conexao->prepare("SELECT COUNT(*) AS total FROM categorias WHERE nome = ? AND id != ?");
    $check->bind_param("si", $nome, $id);
    $check->execute();
    $exists = $check->get_result()->fetch_assoc();

    if ($exists['total'] > 0) {
        echo "<script>
                alert('Já existe uma categoria com este nome!');
                window.location.href = '../pages/cadastro_categoria.php';
              </script>";
        exit;
    }

    $sql = "UPDATE categorias SET nome = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", $nome, $id);
    $stmt->execute();

    header('Location: ../pages/cadastro_categoria.php');
    exit();
}
?>
