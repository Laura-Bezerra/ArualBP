<?php
include_once('../includes/config.php');

if (!empty($_POST['setor_id']) && !empty($_POST['gerente_id'])) {
  $stmt = $conexao->prepare("UPDATE setores SET gerente_id=? WHERE id=?");
  $stmt->bind_param("ii", $_POST['gerente_id'], $_POST['setor_id']);
  $stmt->execute();
}

header("Location: ../pages/cadastro_setor.php");
exit;
?>
