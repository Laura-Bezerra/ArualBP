<?php
session_start();
include_once('config.php');

// Verifica se o usuário é admin
if ($_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Verifica se a ação foi enviada e se o ID da solicitação foi passado
if (isset($_POST['acao']) && isset($_POST['solicitacao_id'])) {
    $acao = $_POST['acao'];
    $solicitacao_id = $_POST['solicitacao_id'];

    // Define o novo status baseado na ação
    if ($acao == 'aprovar') {
        $novo_status = 'APROVADO';
    } elseif ($acao == 'recusar') {
        $novo_status = 'RECUSADO';
    }

    // Atualiza o status da solicitação na tabela solicitacoes
    $sql = "UPDATE solicitacoes SET status = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('si', $novo_status, $solicitacao_id);

    // Executa a query
    if ($stmt->execute()) {
        // Redireciona de volta para a página de solicitações
        header('Location: solicitacoes_alteracoes.php');
        exit();
    } else {
        // Se falhar, exibe um erro
        echo "Erro ao atualizar a solicitação.";
    }
}
?>
