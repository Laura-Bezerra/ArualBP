<?php
include_once('../includes/config.php');

$bp_id = $_GET['bp_id'] ?? 0;

$sql = "SELECT e.codigo_etiqueta, b.descricao 
        FROM etiquetas_bp e
        JOIN bps b ON e.bp_id = b.id
        WHERE e.bp_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $bp_id);
$stmt->execute();
$result = $stmt->get_result();

$etiquetas = [];
while ($row = $result->fetch_assoc()) {
    $etiquetas[] = [
        'codigo' => $row['codigo_etiqueta'],
        'descricao' => $row['descricao']
    ];
}

header('Content-Type: application/json');
echo json_encode($etiquetas);
