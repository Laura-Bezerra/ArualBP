<?php
require_once('../includes/config.php');
header('Content-Type: application/json; charset=UTF-8');

// === BPs por setor (TOP 5) ===
$sqlSetores = "
  SELECT s.nome AS setor, COALESCE(SUM(b.quantidade), 0) AS total
  FROM setores s
  LEFT JOIN bps b ON b.setor_id = s.id
  GROUP BY s.nome
  ORDER BY total DESC
  LIMIT 5
";
$resultSetores = $conexao->query($sqlSetores);

$labelsSetores = [];
$dadosSetores = [];

while ($row = $resultSetores->fetch_assoc()) {
  $labelsSetores[] = $row['setor'];
  $dadosSetores[]  = (int)$row['total'];
}

// === Estado dos itens ===
$sqlEstado = "
  SELECT estado_item, COUNT(*) AS total
  FROM bps
  GROUP BY estado_item
  ORDER BY total DESC
";
$resultEstado = $conexao->query($sqlEstado);

$labelsEstado = [];
$dadosEstado = [];

while ($row = $resultEstado->fetch_assoc()) {
  $labelsEstado[] = $row['estado_item'];
  $dadosEstado[]  = (int)$row['total'];
}

// === Retorna tudo em JSON ===
echo json_encode([
  'bpsPorSetor' => [
    'labels' => $labelsSetores,
    'data'   => $dadosSetores
  ],
  'estadoItens' => [
    'labels' => $labelsEstado,
    'data'   => $dadosEstado
  ]
]);
