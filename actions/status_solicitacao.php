<?php
session_start();
include_once('../includes/config.php');

if (!isset($_SESSION['nivel_acesso']) || !in_array($_SESSION['nivel_acesso'], ['gerente', 'admin'])) {
    header('Location: ../login.php');
    exit();
}

if (isset($_POST['acao']) && isset($_POST['solicitacao_id'])) {
    $acao = $_POST['acao'];
    $solicitacao_id = intval($_POST['solicitacao_id']);

    if ($acao === 'aprovar') {
        $novo_status = 'aprovado';
    } elseif ($acao === 'recusar') {
        $novo_status = 'recusado';
    } else {
        $novo_status = 'pendente';
    }

    // 🔹 Atualiza o status da solicitação
    $sql = "UPDATE solicitacoes SET status = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('si', $novo_status, $solicitacao_id);

    if ($stmt->execute()) {
        // Retorna o gerente para a tela de solicitações
        header('Location: ../pages/gerenciar_solicitacoes.php?msg=success');
        exit();
    } else {
        echo "Erro ao atualizar a solicitação: " . $conexao->error;
    }
} else {
    header('Location: ../pages/gerenciar_solicitacoes.php');
    exit();
}
?>
