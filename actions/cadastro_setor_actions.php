<?php
include_once('../includes/config.php');

// === CADASTRAR NOVO SETOR ===
if (isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);

    // Por padrão, nenhum usuário vinculado no momento do cadastro
    $sql = "INSERT INTO setores (nome, usuario_id) VALUES (?, NULL)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $nome);

    if ($stmt->execute()) {
        header("Location: ../pages/cadastro_setor.php?status=success");
        exit;
    } else {
        echo "Erro ao cadastrar setor: " . $stmt->error;
    }
}


// === ATUALIZAR SETOR EXISTENTE ===
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = trim($_POST['nome']);
    $usuario_id = !empty($_POST['usuario_id']) ? $_POST['usuario_id'] : null;

    $sql = "UPDATE setores SET nome=?, usuario_id=? WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sii", $nome, $usuario_id, $id);

    if ($stmt->execute()) {
        header("Location: ../pages/cadastro_setor.php?status=updated");
        exit;
    } else {
        echo "Erro ao atualizar setor: " . $stmt->error;
    }
}
?>
