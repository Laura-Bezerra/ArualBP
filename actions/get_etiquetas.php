<?php 
include_once('../includes/config.php');

$bp_id = $_GET['bp_id'] ?? 0;

// 🔹 Busca etiquetas com nome do item e setor
$sql = "
    SELECT 
        e.codigo_etiqueta AS codigo,
        b.descricao,
        b.nome_item,
        s.nome AS setor_nome
    FROM etiquetas_bp e
    JOIN bps b ON e.bp_id = b.id
    LEFT JOIN setores s ON b.setor_id = s.id
    WHERE e.bp_id = ?
";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $bp_id);
$stmt->execute();
$result = $stmt->get_result();

$etiquetas = [];
while ($row = $result->fetch_assoc()) {
    $etiquetas[] = [
        'codigo' => $row['codigo'],
        'descricao' => $row['descricao'],
        'nome_item' => $row['nome_item'],
        'setor_nome' => $row['setor_nome']
    ];
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($etiquetas);
