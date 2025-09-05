<?php
// Incluir o arquivo de configuração para conexão com o banco de dados
include_once('config.php');

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber os dados do formulário
    $setor_id = $_POST['setor_id'];
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $valor_total = $_POST['valor_total'];
    $especificacoes_tecnicas = $_POST['especificacoes_tecnicas'];
    $local = $_POST['local'];

    // Validar os dados recebidos
    if (empty($quantidade) || empty($descricao) || empty($marca) || empty($modelo) || empty($data_aquisicao) || empty($valor_total) || empty($especificacoes_tecnicas) || empty($local)) {
        echo "Todos os campos são obrigatórios!";
    } else {
        $sql = "INSERT INTO bps (setor_id, quantidade, descricao, marca, modelo, data_aquisicao, valor_total, especificacoes_tecnicas, local)
                VALUES ('$setor_id', '$quantidade', '$descricao', '$marca', '$modelo', '$data_aquisicao', '$valor_total', '$especificacoes_tecnicas', '$local')";

        if ($conexao->query($sql) === TRUE) {
            header('Location: cadastro_bp.php');
            $message = "Novo bem cadastrado com sucesso!";
            
        } else {
            // Se houver erro, mostrar a mensagem de erro
            $message = "Erro ao cadastrar o bem: " . $conexao->error;
        }
    }
}


