<?php
date_default_timezone_set('America/Sao_Paulo');

$nivel = $_SESSION['nivel_acesso'] ?? '';
$usuario_id = $_SESSION['id'] ?? '';

if (!in_array($nivel, ['admin', 'gerente'])) {
    die('Acesso negado. Este relatório é restrito a administradores e gerentes.');
}

$query = "
    SELECT 
        s.id AS setor_id,
        s.nome AS setor_nome,
        s.codigo AS setor_codigo,
        u.nome AS unidade_nome
    FROM setores s
    LEFT JOIN unidades u ON s.unidade_id = u.id
    ORDER BY u.nome, s.nome
";

$result = $conexao->query($query);

if (!$result || $result->num_rows === 0) {
    $pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 35, 15);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'I', 12);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 10, 'Nenhum setor encontrado no sistema.', 0, 1, 'C');
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('Relatorio_Setores.pdf', 'I');
    exit;
}


$pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(15, 35, 15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(68, 18, 129);
$pdf->Cell(0, 10, 'Relatório de Setores Cadastrados', 0, 1, 'C');
$pdf->Ln(8);


$colId = 25;
$colNome = 90;
$colCodigo = 40;
$colUnidade = 100;

$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(145, 90, 211);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($colId, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell($colNome, 8, 'Nome do Setor', 1, 0, 'C', true);
$pdf->Cell($colCodigo, 8, 'Código', 1, 0, 'C', true);
$pdf->Cell($colUnidade, 8, 'Unidade', 1, 1, 'C', true);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$fill = false;

while ($row = $result->fetch_assoc()) {
    $id = $row['setor_id'];
    $nome = $row['setor_nome'];
    $codigo = $row['setor_codigo'] ?: '-';
    $unidade = $row['unidade_nome'] ?: 'Sem unidade vinculada';

    $pdf->SetFillColor($fill ? 242 : 255, $fill ? 242 : 255, $fill ? 242 : 255);
    $pdf->Cell($colId, 8, $id, 1, 0, 'C', $fill);
    $pdf->Cell($colNome, 8, $nome, 1, 0, 'L', $fill);
    $pdf->Cell($colCodigo, 8, $codigo, 1, 0, 'C', $fill);
    $pdf->Cell($colUnidade, 8, $unidade, 1, 1, 'L', $fill);

    $fill = !$fill;
}

if (ob_get_length()) ob_end_clean();
$pdf->Output('Relatorio_Setores.pdf', 'D');
exit;
