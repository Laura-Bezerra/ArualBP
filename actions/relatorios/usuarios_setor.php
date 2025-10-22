<?php
$setor_id = $_GET['setor_id'] ?? '';
$nivel = $_SESSION['nivel_acesso'] ?? '';
$usuario_id = $_SESSION['id'] ?? '';

$query = "
    SELECT 
        s.nome AS setor,
        u.nome AS nomeUsuario,
        u.usuario,
        u.email,
        u.nivel_acesso
    FROM usuarios u
    INNER JOIN setor_usuarios su ON su.usuario_id = u.id
    INNER JOIN setores s ON s.id = su.setor_id
";

$filtros = [];

if ($nivel === 'gerente') {
    $filtros[] = "s.gerente_id = " . intval($usuario_id);
}
if (!empty($setor_id)) {
    $filtros[] = "s.id = " . intval($setor_id);
}
if (!empty($filtros)) {
    $query .= " WHERE " . implode(' AND ', $filtros);
}
$query .= " ORDER BY s.nome, u.nome";

$result = $conexao->query($query);

if (!$result || $result->num_rows === 0) {
    $pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 35, 15);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'I', 12);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 10, 'Nenhum registro encontrado para os filtros selecionados.', 0, 1, 'C');
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('Relatorio_Usuarios_por_Setor.pdf', 'I');
    exit;
}

if (!empty($setor_id)) {
    $setor_nome = $conexao->query("SELECT nome FROM setores WHERE id = " . intval($setor_id))->fetch_assoc()['nome'] ?? '';
    $titulo = 'Relatório de Usuários - Setor: ' . $setor_nome;
} else {
    $titulo = 'Relatório de Usuários por Setor - Todos os Setores';
}

$pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setHeaderTitle('Relatório de Usuários por Setor');
$pdf->SetMargins(15, 35, 15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(68, 18, 129);
$pdf->Cell(0, 10, $titulo, 0, 1, 'C');
$pdf->Ln(8);

$colNome = 70;
$colUsuario = 45;
$colEmail = 100;
$colNivel = 30;

$currentSetor = null;
$fill = false;

while ($row = $result->fetch_assoc()) {
    $setor      = $row['setor'] ?: 'Não definido';
    $nome       = $row['nomeUsuario'];
    $usuario    = $row['usuario'];
    $email      = $row['email'];
    $nivelUser  = $row['nivel_acesso'];

    if ($setor !== $currentSetor) {
        if ($currentSetor !== null) $pdf->Ln(6);

        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(68, 18, 129);
        $pdf->Cell(0, 10, 'Setor: ' . $setor, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetFillColor(145, 90, 211);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell($colNome, 8, 'Nome', 1, 0, 'C', true);
        $pdf->Cell($colUsuario, 8, 'Usuário', 1, 0, 'C', true);
        $pdf->Cell($colEmail, 8, 'E-mail', 1, 0, 'C', true);
        $pdf->Cell($colNivel, 8, 'Nível', 1, 1, 'C', true);

        $currentSetor = $setor;
        $fill = false;
    }

    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor($fill ? 242 : 255, $fill ? 242 : 255, $fill ? 242 : 255);

    $pdf->Cell($colNome, 8, $nome, 1, 0, 'L', $fill);
    $pdf->Cell($colUsuario, 8, $usuario, 1, 0, 'L', $fill);
    $pdf->Cell($colEmail, 8, $email, 1, 0, 'L', $fill);
    $pdf->Cell($colNivel, 8, $nivelUser, 1, 1, 'C', $fill);

    $fill = !$fill;
}

if (ob_get_length()) ob_end_clean();
$pdf->Output('Relatorio_Usuarios_por_Setor.pdf', 'D');
exit;
