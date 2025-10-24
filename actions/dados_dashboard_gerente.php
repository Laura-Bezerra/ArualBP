<?php
session_start();
require_once('../includes/config.php');
header('Content-Type: application/json; charset=UTF-8');

$gerente_id = $_SESSION['id'];

// Setores do gerente
$sqlSetores = "SELECT id, nome FROM setores WHERE gerente_id = $gerente_id";
$result = $conexao->query($sqlSetores);

$setores = [];
while ($row = $result->fetch_assoc()) {
  $setores[$row['id']] = $row['nome'];
}
$ids = implode(',', array_keys($setores) ?: [0]);

// BPs por setor
$sqlBps = "
  SELECT s.nome AS setor, COALESCE(SUM(b.quantidade),0) AS total
  FROM setores s
  LEFT JOIN bps b ON b.setor_id = s.id
  WHERE s.id IN ($ids)
  GROUP BY s.nome
";
$resBps = $conexao->query($sqlBps);
$labelsBps = []; $dataBps = [];
while ($r = $resBps->fetch_assoc()) {
  $labelsBps[] = $r['setor'];
  $dataBps[] = (int)$r['total'];
}

// Estado dos Itens
$sqlEstado = "
  SELECT estado_item, COUNT(*) AS total
  FROM bps
  WHERE setor_id IN ($ids)
  GROUP BY estado_item
";
$resEstado = $conexao->query($sqlEstado);
$labelsEstado = []; $dataEstado = [];
while ($r = $resEstado->fetch_assoc()) {
  $labelsEstado[] = $r['estado_item'];
  $dataEstado[] = (int)$r['total'];
}

echo json_encode([
  'bpsPorSetor' => ['labels' => $labelsBps, 'data' => $dataBps],
  'estadoItens' => ['labels' => $labelsEstado, 'data' => $dataEstado]
]);
