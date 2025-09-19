<?php
session_start();
include_once('../includes/config.php');

if ($_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['acao']) && isset($_POST['solicitacao_id'])) {
    $acao = $_POST['acao'];
    $solicitacao_id = $_POST['solicitacao_id'];

    if ($acao == 'aprovar') {
        $novo_status = 'APROVADO';
    } elseif ($acao == 'recusar') {
        $novo_status = 'RECUSADO';
    }

    $sql = "UPDATE solicitacoes SET status = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('si', $novo_status, $solicitacao_id);

    if ($stmt->execute()) {
        header('Location: ../pages/gerenciar_solicitacoes.php');
        exit();
    } else {
        echo "Erro ao atualizar a solicitação.";
    }
}
?>
