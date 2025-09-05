<?php
session_start();
include_once('config.php');
require_once('tcpdf/tcpdf.php'); // Incluir o TCPDF

// Verifica se o usuário está logado e tem acesso
if ($_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Se o parâmetro para gerar o PDF for enviado
if (isset($_GET['gerar_pdf'])) {
    gerarRelatorioPDF();
} else {
    
}

function gerarRelatorioPDF() {
    global $conexao;

    // Obtém o setor selecionado, se houver
    $setorId = isset($_GET['setor']) && !empty($_GET['setor']) ? $_GET['setor'] : null;

    // Consulta SQL para pegar os dados dos BPs por setor
    if ($setorId) {
        $sql = "SELECT b.id, st.nome AS setor, b.descricao, b.quantidade, b.marca, b.modelo, b.valor_total
                FROM bps b
                JOIN setores st ON b.setor_id = st.id
                WHERE b.setor_id = ?";
    } else {
        $sql = "SELECT b.id, st.nome AS setor, b.descricao, b.quantidade, b.marca, b.modelo, b.valor_total
                FROM bps b
                JOIN setores st ON b.setor_id = st.id";
    }

    // Prepara e executa a consulta
    $stmt = $conexao->prepare($sql);
    if ($setorId) {
        $stmt->bind_param("i", $setorId); // Ligando o parâmetro do setor
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Obter o nome do setor, se estiver selecionado
    if ($setorId) {
        $setorNomeQuery = $conexao->query("SELECT nome FROM setores WHERE id = $setorId");
        $setorNome = $setorNomeQuery->fetch_assoc()['nome'];
    } else {
        $setorNome = 'Todos os Setores'; // Se não houver setor selecionado
    }

    // Verifica se há resultados
    if ($result->num_rows > 0) {
        // Cria uma nova instância do TCPDF
        $pdf = new TCPDF();

        // Define as configurações básicas do PDF
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $tituloRelatorio = 'Relatório de BPs - Setor: ' . $setorNome; // Usando o nome do setor no título
        $pdf->Cell(0, 10, $tituloRelatorio, 0, 1, 'C');

        // Definir o conteúdo da tabela
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(20, 10, 'ID', 1);
        $pdf->Cell(40, 10, 'Setor', 1);
        $pdf->Cell(60, 10, 'Descricao', 1);
        $pdf->Cell(30, 10, 'Quantidade', 1);
        $pdf->Cell(30, 10, 'Marca', 1);
        $pdf->Cell(30, 10, 'Modelo', 1);
        $pdf->Cell(40, 10, 'Valor Total', 1);
        $pdf->Ln();

        // Adiciona os dados de cada BP na tabela
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['id'], 1);
            $pdf->Cell(40, 10, $row['setor'], 1);
            $pdf->Cell(60, 10, $row['descricao'], 1);
            $pdf->Cell(30, 10, $row['quantidade'], 1);
            $pdf->Cell(30, 10, $row['marca'], 1);
            $pdf->Cell(30, 10, $row['modelo'], 1);
            $pdf->Cell(40, 10, 'R$ ' . number_format($row['valor_total'], 2, ',', '.'), 1);
            $pdf->Ln();
        }

        // Gera o arquivo PDF e força o download
        $pdf->Output('relatorio_bps_por_setor.pdf', 'D');
        exit();
    } else {
        echo "Nenhum dado encontrado para gerar o relatório.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios de BPs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #d9d9d9;
            color: #441281;
            font-family: 'Arial', sans-serif;
            margin-top: 80px; /* Espaço para o menu fixo */
        }

        h1 {
            color: #441281;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Navbar */
        .navbar {
            background-color: #441281;
        }
        .navbar .navbar-brand, .navbar .navbar-nav .nav-link {
            color: #f5ad00;
            border-radius: 5px;
            padding: 10px 30px;
            transition: background-color 0.3s ease;
        }
        .navbar .navbar-nav .nav-link:hover {
            background-color: #f5ad00;
            color: #441281;
        }
        .navbar-toggler {
            background-color: #f5ad00;
        }

        /* Botões */
        button[type="submit"] {
            background-color: #915ad3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #7c4fa6;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

    </style>
</head>
<body>

    <!-- Navbar Fixa -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CONTROLE BP's</a>
            <a href="sistema.php" class="btn btn-danger me-5">Início</a>                        
            <a href="sair.php" class="btn btn-danger">Sair</a>
        </div>
    </nav>

    <!-- Saudação ao usuário -->
    <h1>Relatórios de BPs</h1>

    <!-- Formulário de seleção de setor -->
    <div class="container">
        <form action="relatorios.php" method="get" class="form-inline">
            <div class="mb-3">
                <label for="setor">Escolha o Setor:</label>
                <select name="setor" id="setor" class="form-select">
                    <option value="">Todos os Setores</option>
                    <?php
                    // Consulta para pegar os setores disponíveis
                    $setoresResult = $conexao->query("SELECT id, nome FROM setores");
                    while ($setor = $setoresResult->fetch_assoc()) {
                        echo "<option value='{$setor['id']}'>{$setor['nome']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="gerar_pdf" value="true" class="btn btn-primary">Gerar Relatório</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
