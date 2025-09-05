<?php
// Incluindo a conexão com o banco de dados
include_once('config.php');

// Verificando se o ID foi passado corretamente na URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Escapando o ID para prevenir injeção de SQL
    $id = mysqli_real_escape_string($conexao, $id);

    // Primeiro, excluímos os registros da tabela 'bps' que fazem referência a este setor
    $sqlDeleteBps = "DELETE FROM bps WHERE setor_id = '$id'";
    mysqli_query($conexao, $sqlDeleteBps);  // Executa a exclusão

    // Agora, excluímos o setor
    $sqlDeleteSetor = "DELETE FROM setores WHERE id = '$id'";

    if (mysqli_query($conexao, $sqlDeleteSetor)) {
        echo "Setor excluído com sucesso!";
        header('Location: cadastro_setor.php');
        exit;
    } else {
        echo "Erro ao excluir o setor: " . mysqli_error($conexao);
    }
} else {
    echo "ID inválido ou não informado.";
    header('Location: cadastro_setor.php');
    exit;
}
?>
