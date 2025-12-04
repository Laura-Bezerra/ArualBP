<?php
include_once('../includes/config.php');

if (!empty($_POST['setor_id']) && !empty($_POST['usuario_id'])) {
  $stmt = $conexao->prepare("INSERT INTO setor_usuarios (setor_id, usuario_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $_POST['setor_id'], $_POST['usuario_id']);
  $stmt->execute();
}

header("Location: ../pages/cadastro_setor.php");
exit;
?>s
