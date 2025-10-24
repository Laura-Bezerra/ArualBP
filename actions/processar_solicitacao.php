<?php
session_start();
include_once('../includes/config.php');

if (isset($_POST['submit'])) {
    $bp_id = intval($_POST['bp_id']);
    $descricao = trim($_POST['descricao']);
    $usuario_id = $_SESSION['id'];
    $tipo = $_POST['tipo_solicitacao'];

    $campo_alterado = $tipo === 'alteracao' ? trim($_POST['campo_alterar']) : null;
    $valor_atual = $tipo === 'alteracao' ? trim($_POST['valor_atual']) : null;
    $novo_valor = $tipo === 'alteracao' ? trim($_POST['novo_valor']) : null;

    $sqlSetor = "SELECT setor_id FROM bps WHERE id = ?";
    $stmt = $conexao->prepare($sqlSetor);
    $stmt->bind_param("i", $bp_id);
    $stmt->execute();
    $resultSetor = $stmt->get_result()->fetch_assoc();
    $setor_id = $resultSetor['setor_id'];

    $sqlInsert = "INSERT INTO solicitacoes 
        (bp_id, setor_id, usuario_id, tipo, campo_alterado, valor_atual, novo_valor, descricao, status, data_solicitacao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendente', NOW())";
    $stmt = $conexao->prepare($sqlInsert);
    $stmt->bind_param("iiisssss", $bp_id, $setor_id, $usuario_id, $tipo, $campo_alterado, $valor_atual, $novo_valor, $descricao);
    $stmt->execute();

    header("Location: ../pages/home_usuario.php?setor_selecionado=$setor_id&success=1");
    exit;
}
?>
