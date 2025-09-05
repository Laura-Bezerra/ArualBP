<?php
// Inclui o arquivo de configuração para conexão com o banco de dados
include('config.php');

// Verifica se o formulário foi enviado e os dados necessários estão definidos
if (isset($_POST['descricao'])) {
    
    // Recebe os dados do formulário
    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $quantidade = $_POST['quantidade'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $valor_total = $_POST['valor_total'];
    $especificacoes_tecnicas = $_POST['especificacoes_tecnicas'];
    $local = $_POST['local'];

    // Atualiza os dados no banco de dados
    $sql = "UPDATE bps SET descricao='$descricao', marca='$marca', modelo='$modelo', quantidade='$quantidade', data_aquisicao='$data_aquisicao', valor_total='$valor_total', especificacoes_tecnicas='$especificacoes_tecnicas', local='$local' WHERE id='$id'";

    if (mysqli_query($conexao, $sql)) {
        echo "Atualização bem-sucedida!";
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conexao);
    }

    // Fecha a conexão
    mysqli_close($conexao);
} else {
    echo "Dados incompletos para atualização.";
}
?>
