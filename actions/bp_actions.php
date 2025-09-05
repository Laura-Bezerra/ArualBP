<?php
include_once('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $descricao = $_POST['descricao'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $quantidade = $_POST['quantidade'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $valor_total = $_POST['valor_total'];
    $especificacoes = $_POST['especificacoes_tecnicas'] ?? '';
    $local = $_POST['local'];
    $setor_id = $_POST['setor_id'];

    if ($id) {
        // Atualiza
        $sql = "UPDATE bps 
                SET descricao=?, marca=?, modelo=?, quantidade=?, data_aquisicao=?, valor_total=?, especificacoes_tecnicas=?, local=? 
                WHERE id=?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssisdssi", $descricao, $marca, $modelo, $quantidade, $data_aquisicao, $valor_total, $especificacoes, $local, $id);
        $stmt->execute();
    } else {
        // Insere
        $sql = "INSERT INTO bps (descricao, marca, modelo, quantidade, data_aquisicao, valor_total, especificacoes_tecnicas, local, setor_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssisdssi", $descricao, $marca, $modelo, $quantidade, $data_aquisicao, $valor_total, $especificacoes, $local, $setor_id);
        $stmt->execute();
    }

    header("Location: ../pages/cadastro_bp.php?setor_id=$setor_id");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $setor_id = $_GET['setor_id'];
    $sql = "DELETE FROM bps WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: ../pages/cadastro_bp.php?setor_id=$setor_id");
    exit();
}
