<?php
session_start();
include_once('../includes/config.php');
require_once('../tcpdf/tcpdf.php');

$tipo = $_GET['tipo_relatorio'] ?? '';

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

switch($tipo) {
    case 'bps_setor':
        $setor = $_GET['setor'] ?? null;
        if($setor) {
            $setorResult = $conexao->query("SELECT nome FROM setores WHERE id = $setor");
            $setorNome = $setorResult->num_rows > 0 ? $setorResult->fetch_assoc()['nome'] : 'Setor Desconhecido';
            $stmt = $conexao->prepare("SELECT b.id, b.descricao, b.quantidade, b.marca, b.modelo FROM bps b WHERE b.setor_id = ?");
            $stmt->bind_param("i", $setor);
        } else {
            $stmt = $conexao->prepare("SELECT b.id, b.descricao, b.quantidade, b.marca, b.modelo FROM bps b");
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $pdf->Cell(0,10,'BPs por Setor: '. $setorNome ,0,1,'C');
        $pdf->Ln();
        $pdf->Cell(20,10,'ID',1);
        $pdf->Cell(60,10,'Descricao',1);
        $pdf->Cell(30,10,'Quantidade',1);
        $pdf->Cell(30,10,'Marca',1);
        $pdf->Cell(30,10,'Modelo',1);
        $pdf->Ln();

        while($row = $result->fetch_assoc()) {
            $pdf->Cell(20,10,$row['id'],1);
            $pdf->Cell(60,10,$row['descricao'],1);
            $pdf->Cell(30,10,$row['quantidade'],1);
            $pdf->Cell(30,10,$row['marca'],1);
            $pdf->Cell(30,10,$row['modelo'],1);
            $pdf->Ln();
        }
        break;

    case 'bps_data':
        $data_inicio = $_GET['data_inicio'] ?? '';
        $data_fim = $_GET['data_fim'] ?? '';
        break;

    case 'usuarios':
        break;

    default:
        die('Relatório inválido');
}

$pdf->Output('relatorio.pdf','D');