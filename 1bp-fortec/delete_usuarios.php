<?php
// Incluindo a conexão com o banco de dados
include_once('config.php');

// Verificando se o ID foi passado corretamente na URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Escapando o ID para prevenir injeção de SQL
    $id = mysqli_real_escape_string($conexao, $id);

    // Preparando a consulta para deletar o usuário
    $sqlDelete = "DELETE FROM usuarios WHERE id = '$id'";

    // Executando a consulta de deleção
    if (mysqli_query($conexao, $sqlDelete)) {
        // Exibe uma mensagem de sucesso
        echo "Usuário excluído com sucesso!";
        // Redireciona de volta para a página de cadastro de usuários
        header("Location: cadastro_usuario.php");
        exit;
    } else {
        // Exibe uma mensagem de erro caso algo falhe
        echo "Erro ao excluir o usuário: " . mysqli_error($conexao);
    }
} else {
    // Caso não encontre o ID
    echo "ID inválido ou não informado.";
    // Redireciona de volta para a página de cadastro de usuários
    header("Location: cadastro_usuario.php");
    exit;
}
?>
