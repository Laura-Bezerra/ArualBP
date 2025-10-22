<?php
date_default_timezone_set('America/Sao_Paulo');
require_once('../includes/config.php');
require_once('../includes/ReportPDF.php');

$nivel = $_SESSION['nivel_acesso'] ?? '';
$usuario_id = $_SESSION['id'] ?? '';

$setor_id = $_GET['setor_id'] ?? '';
$usuario_filtro = $_GET['usuario_id'] ?? '';

$query = "
    SELECT 
        s.id AS setor_id,
        s.nome AS setor_nome,
        u.id AS usuario_id,
        u.nome AS usuario_nome,
        sol.id AS solicitacao_id,
        sol.data_solicitacao,
        sol.status,
        sol.tipo,
        bp.nome_item AS item_nome
    FROM solicitacoes sol
    INNER JOIN bps bp ON bp.id = sol.bp_id
    INNER JOIN setores s ON s.id = bp.setor_id
    INNER JOIN usuarios u ON u.id = sol.usuario_id
";

$filtros = [];

if ($nivel === 'gerente') {
    $filtros[] = "s.gerente_id = " . intval($usuario_id);
} elseif ($nivel === 'usuario') {
    $filtros[] = "u.id = " . intval($usuario_id);
}
if (!empty($setor_id)) $filtros[] = "s.id = " . intval($setor_id);
if (!empty($usuario_filtro)) $filtros[] = "u.id = " . intval($usuario_filtro);

$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim = $_GET['data_fim'] ?? '';
$status_filtro = $_GET['status'] ?? '';

// 🗓️ Filtro por intervalo de datas (corrigido)
if (!empty($data_inicio) && !empty($data_fim)) {
    if ($data_inicio === $data_fim) {
        // Mesmo dia → incluir o dia inteiro
        $filtros[] = "sol.data_solicitacao BETWEEN '" . $conexao->real_escape_string($data_inicio) . " 00:00:00' 
                      AND '" . $conexao->real_escape_string($data_fim) . " 23:59:59'";
    } else {
        $filtros[] = "sol.data_solicitacao BETWEEN '" . $conexao->real_escape_string($data_inicio) . " 00:00:00' 
                      AND '" . $conexao->real_escape_string($data_fim) . " 23:59:59'";
    }
} elseif (!empty($data_inicio)) {
    $filtros[] = "sol.data_solicitacao >= '" . $conexao->real_escape_string($data_inicio) . " 00:00:00'";
} elseif (!empty($data_fim)) {
    $filtros[] = "sol.data_solicitacao <= '" . $conexao->real_escape_string($data_fim) . " 23:59:59'";
}


// 🟣 Filtro por status
if (!empty($status_filtro)) {
    $filtros[] = "LOWER(sol.status) = '" . strtolower($conexao->real_escape_string($status_filtro)) . "'";
}

if ($filtros) $query .= " WHERE " . implode(' AND ', $filtros);
$query .= " ORDER BY s.nome, u.nome, sol.data_solicitacao DESC";

$result = $conexao->query($query);

if (!$result || $result->num_rows === 0) {
    $pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'I', 12);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 10, 'Nenhuma solicitação encontrada.', 0, 1, 'C');
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('Relatorio_Solicitacoes.pdf', 'I');
    exit;
}

// ---------------------------
// CONFIGURAÇÃO DO PDF
// ---------------------------
$pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setHeaderTitle('Relatório de Solicitações');
$pdf->SetMargins(15, 35, 15);
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(68, 18, 129);
$pdf->Cell(0, 10, 'Relatório de Solicitações por Setor e Usuário', 0, 1, 'C');
$pdf->Ln(5);

// ---------------------------
// CONFIGURAÇÃO DA TABELA
// ---------------------------
// total útil ≈ 270mm
$larguras = [28, 90, 50, 40, 30, 32]; // soma = 270
$cabecalhos = ['Solicitação #', 'Item', 'Setor', 'Tipo', 'Data Abertura ', 'Status'];

$currentSetor = null;
$currentUsuario = null;
$fill = false;

// ---------------------------
// IMPRESSÃO AGRUPADA
// ---------------------------
while ($row = $result->fetch_assoc()) {
    $setor = $row['setor_nome'];
    $usuario = $row['usuario_nome'];

    // Quebra automática de página se o setor mudar
    if ($setor !== $currentSetor && $currentSetor !== null) {
        $pdf->AddPage();
    }

    // Cabeçalho de setor
    if ($setor !== $currentSetor) {
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(68, 18, 129);
        $pdf->Cell(0, 10, "Setor: $setor", 0, 1, 'L');
        $pdf->Ln(2);

        // Cabeçalho da tabela (repete em cada setor)
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(145, 90, 211);
        $pdf->SetTextColor(255, 255, 255);
        foreach ($cabecalhos as $i => $label) {
            $pdf->Cell($larguras[$i], 8, $label, 1, 0, 'C', true);
        }
        $pdf->Ln();

        $currentSetor = $setor;
        $currentUsuario = null;
    }

    // Cabeçalho de usuário
    if ($usuario !== $currentUsuario) {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetTextColor(68, 18, 129);
        $pdf->Cell(array_sum($larguras), 7, "Usuário: $usuario", 1, 1, 'L', true);
        $currentUsuario = $usuario;
    }

    // Linha de dados
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor($fill ? 255 : 240);

    $data = date('d/m/Y', strtotime($row['data_solicitacao']));
    $campos = [
        $row['solicitacao_id'],
        $row['item_nome'],
        $row['setor_nome'],
        ucfirst($row['tipo']),
        $data,
        ucfirst($row['status'])
    ];

    foreach ($campos as $i => $valor) {
        $pdf->Cell($larguras[$i], 8, $valor, 1, 0, 'L', $fill);
    }
    $pdf->Ln();
    $fill = !$fill;
}

// ---------------------------
// SAÍDA FINAL
// ---------------------------
if (ob_get_length()) ob_end_clean();
$pdf->Output('Relatorio_Solicitacoes.pdf', 'I');
exit;
