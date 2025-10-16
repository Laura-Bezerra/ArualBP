<?php
include_once('../includes/config.php');

if (isset($_GET['setor_id']) && isset($_GET['usuario'])) {
    $setorId = intval($_GET['setor_id']);
    $usuarioNome = trim($_GET['usuario']);

    // Busca o ID do usuário pelo nome
    $sql = "SELECT id FROM usuarios WHERE nome = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $usuarioNome);
    $stmt->execute();
    $res = $stmt->get_result();
    $usuario = $res->fetch_assoc();

    if ($usuario) {
        $usuarioId = $usuario['id'];

        // Remove o vínculo da tabela setor_usuarios
        $delete = $conexao->prepare("DELETE FROM setor_usuarios WHERE setor_id=? AND usuario_id=?");
        $delete->bind_param("ii", $setorId, $usuarioId);
        $delete->execute();
    }
}

header("Location: ../pages/cadastro_setor.php");
exit;
?>