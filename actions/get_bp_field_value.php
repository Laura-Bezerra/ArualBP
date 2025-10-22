<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


include_once('../includes/config.php');

if (isset($_GET['bp_id']) && isset($_GET['campo'])) {
    $bp_id = intval($_GET['bp_id']);
    $campo = $_GET['campo'];

    // Campos permitidos (por segurança)
    $campos_permitidos = [
        'nome_item', 'descricao', 'marca', 'modelo', 'quantidade',
        'local', 'fornecedor', 'custo_unitario', 'custo_total',
        'condicao_aquisicao', 'estado_item', 'observacoes'
    ];

    if (in_array($campo, $campos_permitidos)) {
        $sql = "SELECT `$campo` AS valor FROM bps WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $bp_id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        echo $resultado['valor'] ?? '';
    } else {
        echo '';
    }
}
?>
