<?php
session_start();
include_once('../includes/config.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link rel="stylesheet" href="../css/relatorios.css">
    <link rel="stylesheet" href="../css/navbar.css"> <!-- garante navbar -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/relatorios.js"></script>
</head>

<body>
    <?php include('../includes/navbar.php'); ?> <!-- agora dentro do <body> -->

    <div class="container mt-5">
        <h1>Relatórios</h1>

        <form id="form-relatorio" action="../actions/gerar_relatorio.php" method="get">
            <div class="mb-3">
                <label for="tipo_relatorio">Tipo de Relatório:</label>
                <select name="tipo_relatorio" id="tipo_relatorio" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="bps_setor">BPs por Setor</option>
                    <option value="bps_data">BPs por Período</option>
                    <?php if ($_SESSION['nivel_acesso'] === 'admin') { ?>
                        <option value="usuarios">Usuários do Sistema (Apenas Admin)</option>
                    <?php } ?>
                    <option value="usuarios_setor">Usuários por Setor</option>
                    <?php if ($_SESSION['nivel_acesso'] === 'admin') { ?>
                        <option value="setores">Setores Cadastrados</option>
                    <?php } ?>
                    <?php if (in_array($_SESSION['nivel_acesso'], ['admin', 'gerente'])) { ?>
                        <option value="categorias">Categorias Cadastradas</option>
                    <?php } ?>
                    <option value="inventario_setor">Inventário por Setor</option>
                    <option value="solicitacoes">Solicitações</option>
                </select>
            </div>

            <div id="parametros-relatorio" class="mb-3" style="display:none;"></div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Gerar Relatório</button>
            </div>
        </form>
    </div>
</body>

</html>