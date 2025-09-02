<?php
session_start();
include_once('config.php');

if (isset($_POST['submit']) && isset($_SESSION['email'])) {
    $logado = $_SESSION['email'];

    // Obter o ID do usuário a partir do email da sessão
    $sqlUsuario = "SELECT id FROM usuarios WHERE email = '$logado'";
    $resultUsuario = $conexao->query($sqlUsuario);
    $usuario_id = $resultUsuario->fetch_assoc()['id'];

    if ($usuario_id) {
        $bp_id = $_POST['bp_id'];
        $descricao = $_POST['descricao'];

        // Insere a solicitação
        $sql = "INSERT INTO solicitacoes (usuario_id, bp_id, setor_id, descricao, data_solicitacao, status)
                VALUES ('$usuario_id', '$bp_id', (SELECT setor_id FROM bps WHERE id = '$bp_id'), '$descricao', NOW(), 'Pendente')";

        if ($conexao->query($sql) === TRUE) {
            header('Location: sistema_usuario.php');
            exit();
        } else {
            echo "Erro ao processar a solicitação: " . $conexao->error;
        }
    } else {
        echo "Erro ao identificar o usuário.";
    }
} else {
    echo "Usuário não logado ou formulário inválido.";
}
?>
