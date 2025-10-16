<?php
include_once('../includes/config.php');

if (isset($_GET['setor_id'])) {
    $setorId = intval($_GET['setor_id']);

    // Define o campo gerente_id como NULL
    $sql = "UPDATE setores SET gerente_id = NULL WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $setorId);
    $stmt->execute();
}

header("Location: ../pages/cadastro_setor.php");
exit;
?>
