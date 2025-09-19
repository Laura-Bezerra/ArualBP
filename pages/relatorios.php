<?php
session_start();
include_once('../includes/config.php');
include_once('../includes/navbar.php'); 
include '../includes/header.php'; 

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link href="../css/relatorios.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/relatorios.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Relatórios</h1>

        <form id="form-relatorio" action="../actions/gerar_relatorio.php" method="get">
            <div class="mb-3">
                <label for="tipo_relatorio">Tipo de Relatório:</label>
                <select name="tipo_relatorio" id="tipo_relatorio" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="bps_setor">BPs por Setor</option>
                    <option value="bps_data">BPs por Período</option>
                    <option value="usuarios">Usuários Cadastrados</option>
                </select>
            </div>
            <div id="parametros-relatorio"></div>

            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
        </form>
    </div>

   
</body>
</html>