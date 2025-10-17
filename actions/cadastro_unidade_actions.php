<?php
include_once('../includes/config.php');
session_start();

// 🔒 Apenas admin pode executar ações
if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// === INSERÇÃO DE UNIDADE ===
if (isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);
    $sigla = strtoupper(trim($_POST['sigla']));
    $descricao = trim($_POST['descricao']);

    // Verifica duplicidade antes de inserir
    $check = $conexao->prepare("SELECT COUNT(*) AS total FROM unidades WHERE nome = ? OR sigla = ?");
    $check->bind_param("ss", $nome, $sigla);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        echo "<script>
                alert('Já existe uma unidade com este nome ou sigla!');
                window.location.href = '../pages/cadastro_unidade.php';
              </script>";
        exit;
    }

    // Inserção
    $sql = "INSERT INTO unidades (nome, sigla, descricao) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sss", $nome, $sigla, $descricao);
    $stmt->execute();

    header('Location: ../pages/cadastro_unidade.php');
    exit();
}

// === ATUALIZAÇÃO DE UNIDADE ===
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);

    // Verifica duplicidade de nome (mas ignora a própria unidade)
    $check = $conexao->prepare("SELECT COUNT(*) AS total FROM unidades WHERE nome = ? AND id != ?");
    $check->bind_param("si", $nome, $id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        echo "<script>
                alert('Já existe outra unidade com este nome!');
                window.location.href = '../pages/cadastro_unidade.php';
              </script>";
        exit;
    }

    // Atualiza apenas nome e descrição
    $sql = "UPDATE unidades SET nome=?, descricao=? WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssi", $nome, $descricao, $id);
    $stmt->execute();

    header('Location: ../pages/cadastro_unidade.php');
    exit();
}
?>
