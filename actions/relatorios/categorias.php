<?php
date_default_timezone_set('America/Sao_Paulo');

$nivel = $_SESSION['nivel_acesso'] ?? '';
$usuario_id = $_SESSION['id'] ?? '';

if (!in_array($nivel, ['admin', 'gerente'])) {
    die('Acesso negado. Este relatório é restrito a administradores e gerentes.');
}

$query = "
    SELECT 
        id AS categoria_id,
        nome AS categoria_nome
    FROM categorias
    ORDER BY nome
";

$result = $conexao->query($query);


if (!$result || $result->num_rows === 0) {
    $pdf = new ReportPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 35, 15);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'I', 12);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 10, 'Nenhuma categoria cadastrada no sistema.', 0, 1, 'C');
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('Relatorio_Categorias.pdf', 'I');
    exit;
}

$pdf = new ReportPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(15, 35, 15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(68, 18, 129);
$pdf->Cell(0, 10, 'Relatório de Categorias Cadastradas', 0, 1, 'C');
$pdf->Ln(8);


$colId = 30;
$colNome = 150;

// Cabeçalho da tabela
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(145, 90, 211);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($colId, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell($colNome, 8, 'Nome da Categoria', 1, 1, 'C', true);

// Linhas de dados
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$fill = false;

while ($row = $result->fetch_assoc()) {
    $id = $row['categoria_id'];
    $nome = $row['categoria_nome'];

    $pdf->SetFillColor($fill ? 242 : 255, $fill ? 242 : 255, $fill ? 242 : 255);
    $pdf->Cell($colId, 8, $id, 1, 0, 'C', $fill);
    $pdf->Cell($colNome, 8, $nome, 1, 1, 'L', $fill);

    $fill = !$fill;
}


if (ob_get_length()) ob_end_clean();
$pdf->Output('Relatorio_Categorias.pdf', 'I');
exit;
