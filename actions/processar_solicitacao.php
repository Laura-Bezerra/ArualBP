<?php
session_start();
include_once('../includes/config.php');

if (isset($_POST['submit'])) {
    $bp_id = intval($_POST['bp_id']);
    $descricao = trim($_POST['descricao']);
    $usuario_id = $_SESSION['id'];

    // 🔹 Busca o setor do BP selecionado
    $sqlSetor = "SELECT setor_id FROM bps WHERE id = ?";
    $stmt = $conexao->prepare($sqlSetor);
    $stmt->bind_param("i", $bp_id);
    $stmt->execute();
    $resultSetor = $stmt->get_result()->fetch_assoc();
    $setor_id = $resultSetor['setor_id'];

    // 🔹 Salva a solicitação
    $sqlInsert = "INSERT INTO solicitacoes (bp_id, setor_id, usuario_id, descricao, status, data_solicitacao)
                  VALUES (?, ?, ?, ?, 'pendente', NOW())";
    $stmt = $conexao->prepare($sqlInsert);
    $stmt->bind_param("iiis", $bp_id, $setor_id, $usuario_id, $descricao);

    if ($stmt->execute()) {
        header("Location: ../pages/solicitacoes.php?success=1");
        exit;
    } else {
        echo "Erro ao enviar solicitação: " . $conexao->error;
    }
} else {
    header("Location: ../pages/home_usuario.php");
    exit;
}
?>
