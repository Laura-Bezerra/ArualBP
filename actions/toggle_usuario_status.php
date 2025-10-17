<?php
include_once('../includes/config.php');

if (!empty($_POST['id'])) {
    $id = intval($_POST['id']);

    // Pega o status atual
    $sql = "SELECT ativo FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $novoStatus = $result['ativo'] ? 0 : 1;

    // Atualiza o status
    $update = $conexao->prepare("UPDATE usuarios SET ativo = ? WHERE id = ?");
    $update->bind_param("ii", $novoStatus, $id);
    $update->execute();

    echo json_encode(['success' => true, 'status' => $novoStatus]);
}
?>
