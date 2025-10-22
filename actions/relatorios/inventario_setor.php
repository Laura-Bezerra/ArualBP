<?php
date_default_timezone_set('America/Sao_Paulo');

// Sessão, conexão e ReportPDF já vêm do gerar_relatorio.php
$nivel = $_SESSION['nivel_acesso'] ?? '';
$usuario_id = $_SESSION['id'] ?? '';

$setor_id = $_GET['setor_id'] ?? '';
$camposSelecionados = $_GET['campos'] ?? [];

// ==========================
// 🔍 Colunas disponíveis
// ==========================
$colunasDisponiveis = [
    'codigo_bp' => 'Código BP',
    'quantidade' => 'Qtd',
    'descricao' => 'Descrição',
    'marca' => 'Marca',
    'data_aquisicao' => 'Data de Aquisição',
    'fornecedor' => 'Fornecedor',
    'condicao_aquisicao' => 'Condição',
    'estado_item' => 'Estado',
    'custo_total' => 'Custo Total',
    'custo_unitario' => 'Custo Unitário',
    'observacoes' => 'Observações',
    'local' => 'Local',
    'categoria' => 'Categoria'
];

// ==========================
// 🧠 Se não marcar nada → imprime só ID e Nome
// ==========================
$colunasValidas = array_intersect(array_keys($colunasDisponiveis), $camposSelecionados);
if (empty($colunasValidas)) $colunasValidas = [];

// ==========================
// 🧩 Monta SELECT dinâmico
// ==========================
$selectCols = "b.id, b.nome_item";
foreach ($colunasValidas as $col) {
    $selectCols .= $col === 'categoria'
        ? ", c.nome AS categoria"
        : ", b.$col";
}

$query = "
    SELECT s.nome AS setor, $selectCols
    FROM bps b
    INNER JOIN setores s ON s.id = b.setor_id
    LEFT JOIN categorias c ON c.id = b.categoria_id
";

$filtros = [];
if ($nivel === 'admin') {
    if (!empty($setor_id)) {
        $filtros[] = "s.id = " . intval($setor_id);
    }
}
elseif ($nivel === 'gerente') {
    if (!empty($setor_id)) {
        $filtros[] = "s.id = " . intval($setor_id) . " AND s.gerente_id = " . intval($usuario_id);
    } else {
        $filtros[] = "s.gerente_id = " . intval($usuario_id);
    }
}

else {
    if (!empty($setor_id)) {
        $filtros[] = "s.id = " . intval($setor_id) . " 
                      AND s.id IN (SELECT setor_id FROM setor_usuarios WHERE usuario_id = " . intval($usuario_id) . ")";
    } else {
        $filtros[] = "s.id IN (SELECT setor_id FROM setor_usuarios WHERE usuario_id = " . intval($usuario_id) . ")";
    }
}

if (!empty($filtros)) {
    $query .= " WHERE " . implode(' AND ', $filtros);
}

$query .= " ORDER BY s.nome, b.nome_item";

$result = $conexao->query($query);
if (!$result || $result->num_rows === 0) {
    $pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'I', 12);
    $pdf->Cell(0, 10, 'Nenhum item encontrado.', 0, 1, 'C');
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('Relatorio_Itens_por_Setor.pdf', 'I');
    exit;
}

// ==========================
// 🧾 PDF CONFIG
// ==========================
$pdf = new ReportPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setHeaderTitle('Relatório de Itens do Inventário por Setor');
$pdf->SetMargins(15, 35, 15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

$currentSetor = null;
$pdf->SetFont('helvetica', '', 9);
$fill = false;

// ==========================
// 🧱 Cabeçalhos dinâmicos
// ==========================
$colunasTabela = ['ID' => 'id', 'Nome do Item' => 'nome_item'];
foreach ($colunasValidas as $col) {
    $colunasTabela[$colunasDisponiveis[$col]] = $col;
}

// Define se é tabela ou ficha
$totalCols = count($colunasTabela);
$modoTabela = $totalCols <= 8;

// Define se há campos longos (que precisam mais espaço)
$temCamposLongos = count(array_intersect(['descricao', 'observacoes', 'fornecedor'], $colunasValidas)) > 0;

// Fonte adaptativa
$tamanhoFonte = $modoTabela
    ? ($temCamposLongos ? 7 : ($totalCols > 6 ? 8 : 9))
    : 9;

// ==========================
// 📊 Impressão por setor
// ==========================
while ($row = $result->fetch_assoc()) {
    $setor = $row['setor'] ?: 'Não definido';

    // Novo setor
    if ($setor !== $currentSetor) {
        if ($currentSetor !== null) $pdf->Ln(6);

        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(68, 18, 129);
        $pdf->Cell(0, 10, 'Setor: ' . $setor, 0, 1, 'L');
        $pdf->Ln(2);

        // --- Cabeçalho sempre repetido em cada setor ---
        if ($modoTabela) {
            $pdf->SetFont('helvetica', 'B', $tamanhoFonte);
            $pdf->SetFillColor(145, 90, 211);
            $pdf->SetTextColor(255, 255, 255);

            $larguraCol = 270 / $totalCols;
            foreach ($colunasTabela as $label => $key) {
                $pdf->Cell($larguraCol, 8, $label, 1, 0, 'C', true);
            }
            $pdf->Ln();
        }

        $currentSetor = $setor;
    }

    // -------- MODO 1: TABELA INTELIGENTE --------
    if ($modoTabela) {
        $pdf->SetFont('helvetica', '', $tamanhoFonte);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor($fill ? 242 : 255);

        $larguraCol = 270 / $totalCols;

        // 1️⃣ calcula a altura máxima da linha
        $alturas = [];
        foreach ($colunasTabela as $label => $key) {
            $valor = $row[$key] ?? '-';
            if (in_array($key, ['custo_total', 'custo_unitario']) && $valor !== '-') {
                $valor = 'R$ ' . number_format((float)$valor, 2, ',', '.');
            } elseif ($key === 'data_aquisicao' && $valor) {
                $valor = date('d/m/Y', strtotime($valor));
            }
            $alturas[] = $pdf->getNumLines($valor, $larguraCol) * 5; // 5mm por linha de texto
        }
        $alturaLinha = max($alturas);

        // 2️⃣ desenha todas as células lado a lado com altura igual
        $xInicial = $pdf->GetX();
        $yInicial = $pdf->GetY();
        foreach ($colunasTabela as $label => $key) {
            $valor = $row[$key] ?? '-';
            if (in_array($key, ['custo_total', 'custo_unitario']) && $valor !== '-') {
                $valor = 'R$ ' . number_format((float)$valor, 2, ',', '.');
            } elseif ($key === 'data_aquisicao' && $valor) {
                $valor = date('d/m/Y', strtotime($valor));
            }

            $x = $pdf->GetX();
            $y = $pdf->GetY();

            // imprime o texto dentro da célula
            $pdf->MultiCell($larguraCol, 5, $valor, 1, 'L', $fill, 0, '', '', true, 0, false, true, $alturaLinha, 'M');
            // volta para o topo da linha para desenhar a próxima coluna
            $pdf->SetXY($x + $larguraCol, $y);
        }

        // 3️⃣ move o cursor para a linha seguinte
        $pdf->Ln($alturaLinha);
        $fill = !$fill;
    }

    // -------- MODO 2: FICHA --------
    else {
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(68, 18, 129);
        $pdf->Cell(0, 8, "ID {$row['id']} - {$row['nome_item']}", 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(0, 0, 0);

        foreach ($colunasValidas as $col) {
            $label = $colunasDisponiveis[$col];
            $valor = $row[$col] ?? '-';
            if (in_array($col, ['custo_total', 'custo_unitario']) && $valor !== '-') {
                $valor = 'R$ ' . number_format((float)$valor, 2, ',', '.');
            } elseif ($col === 'data_aquisicao' && $valor) {
                $valor = date('d/m/Y', strtotime($valor));
            }

            $pdf->MultiCell(0, 6, "$label: $valor", 0, 'L', false);
        }

        $pdf->Ln(3);
        $pdf->SetDrawColor(145, 90, 211);
        $pdf->Line(15, $pdf->GetY(), 282, $pdf->GetY());
        $pdf->Ln(3);
    }
}

if (ob_get_length()) ob_end_clean();
$pdf->Output('Relatorio_Itens_por_Setor.pdf', 'I');
exit;
