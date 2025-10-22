<?php
// Sessão, conexão e ReportPDF já estão carregados pelo gerar_relatorio.php
date_default_timezone_set('America/Sao_Paulo');

$nivel = $_SESSION['nivel_acesso'] ?? '';
$usuario_id = $_SESSION['id'] ?? '';

if ($nivel !== 'admin') {
    die('Acesso negado. Este relatório é restrito a administradores.');
}

$filtro_status = $_GET['status_usuarios'] ?? 'todos'; // "ativos", "inativos" ou "todos"

// ==========================
// 🔍 Query base
// ==========================
$query = "
    SELECT 
        u.nome,
        u.usuario,
        u.email,
        u.nivel_acesso,
        u.ativo
    FROM usuarios u
";

if ($filtro_status === 'ativos') {
    $query .= " WHERE u.ativo = 1";
} elseif ($filtro_status === 'inativos') {
    $query .= " WHERE u.ativo = 0";
}

$query .= " ORDER BY u.nivel_acesso, u.nome";

$result = $conexao->query($query);

// ==========================
// ⚠️ Nenhum resultado
// ==========================
if (!$result || $result->num_rows === 0) {
    $pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 35, 15);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'I', 12);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 10, 'Nenhum registro encontrado para o filtro selecionado.', 0, 1, 'C');
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('Relatorio_Todos_Usuarios.pdf', 'I');
    exit;
}

// ==========================
// 🧾 Título dinâmico
// ==========================
switch ($filtro_status) {
    case 'ativos':
        $titulo = 'Relatório de Usuários Ativos';
        break;
    case 'inativos':
        $titulo = 'Relatório de Usuários Inativos';
        break;
    default:
        $titulo = 'Relatório de Todos os Usuários do Sistema';
}

// ==========================
// 🧩 Geração do PDF
// ==========================
$pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(15, 35, 15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

// Cabeçalho principal
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(68, 18, 129);
$pdf->Cell(0, 10, $titulo, 0, 1, 'C');
$pdf->Ln(8);

// Largura das colunas
$colNome = 70;
$colUsuario = 45;
$colEmail = 90;
$colNivel = 35;
$colStatus = 30;

// Cabeçalho da tabela
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(145, 90, 211);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($colNome, 8, 'Nome', 1, 0, 'C', true);
$pdf->Cell($colUsuario, 8, 'Usuário', 1, 0, 'C', true);
$pdf->Cell($colEmail, 8, 'E-mail', 1, 0, 'C', true);
$pdf->Cell($colNivel, 8, 'Nível de Acesso', 1, 0, 'C', true);
$pdf->Cell($colStatus, 8, 'Status', 1, 1, 'C', true);

// Linhas de dados
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$fill = false;

while ($row = $result->fetch_assoc()) {
    $nome       = $row['nome'];
    $usuario    = $row['usuario'];
    $email      = $row['email'];
    $nivelUser  = ucfirst($row['nivel_acesso']);
    $status     = ($row['ativo'] == 1) ? 'Ativo' : 'Inativo';

    $pdf->SetFillColor($fill ? 242 : 255, $fill ? 242 : 255, $fill ? 242 : 255);
    $pdf->Cell($colNome, 8, $nome, 1, 0, 'L', $fill);
    $pdf->Cell($colUsuario, 8, $usuario, 1, 0, 'L', $fill);
    $pdf->Cell($colEmail, 8, $email, 1, 0, 'L', $fill);
    $pdf->Cell($colNivel, 8, $nivelUser, 1, 0, 'C', $fill);
    $pdf->Cell($colStatus, 8, $status, 1, 1, 'C', $fill);

    $fill = !$fill;
}

// ==========================
// 🧾 Saída final
// ==========================
if (ob_get_length()) ob_end_clean();
$pdf->Output('Relatorio_Todos_Usuarios.pdf', 'D');
exit;
