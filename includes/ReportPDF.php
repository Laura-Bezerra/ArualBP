<?php
require_once('../includes/tcpdf/tcpdf.php');

class ReportPDF extends TCPDF {

    public function Header() {
        // Caminho da logo
        $image_file = '../includes/img/logo_nome_arualbp.png';
        if (file_exists($image_file)) {
            $this->Image($image_file, 15, 10, 50, '', 'PNG', '', 'T', false, 300);
        }

        // Título padrão (pode ser sobrescrito no controller)
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(68, 18, 129);
        $this->Cell(0, 10, $this->header_title ?? 'Relatório ArualBP', 0, 1, 'C');

        // Linha divisória
        $this->SetDrawColor(145, 90, 211);
        $this->Line(15, 25, 282, 25);
        $this->Ln(8);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Gerado em ' . date('d/m/Y H:i'), 0, 0, 'R');
    }

    // Método de conveniência
    public function setHeaderTitle($title) {
        $this->header_title = $title;
    }
}
?>
