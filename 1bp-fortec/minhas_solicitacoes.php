<?php
session_start();
include_once('config.php');

if (isset($_SESSION['id'])) {
    $usuario_id = $_SESSION['id'];

    // Ajuste na consulta SQL para permitir múltiplos setores
    $sql = "SELECT solicitacoes.id, solicitacoes.descricao, solicitacoes.data_solicitacao, solicitacoes.status,
                   bps.descricao AS bp_descricao
            FROM solicitacoes
            JOIN bps ON solicitacoes.bp_id = bps.id
            WHERE solicitacoes.setor_id IN (SELECT id FROM setores WHERE usuario_id = '$usuario_id')";

    // Tenta executar a consulta
    $result = $conexao->query($sql);

    // Verifica se a consulta foi executada corretamente
    if ($result === false) {
        echo "<p class='error'>Erro ao executar a consulta: " . $conexao->error . "</p>";
    } else {
        // Verifica o número de resultados
        if ($result->num_rows > 0) {
            $solicitacoes = [];
            while ($row = $result->fetch_assoc()) {
                $solicitacoes[] = $row;
            }
        } else {
            $solicitacoes = [];
        }
    }
} else {
    echo "<p class='error'>Usuário não logado.</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Alteração</title>
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

        /* Tabela */
        .solicitacoes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .solicitacoes-table th, .solicitacoes-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .solicitacoes-table th {
            background-color: #915ad3;
            color: white;
        }

        .solicitacoes-table tr:hover {
            background-color: #f1f1f1;
        }

        .no-results, .error {
            text-align: center;
            color: red;
            font-size: 18px;
        }

        .voltar-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #f5ad00;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .voltar-btn:hover {
            background-color: #e59c00;
        }
    </style>
</head>
<body>

    <!-- Navbar Fixa -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CONTROLE BP's</a>
            <a href="sistema_usuario.php" class="btn btn-danger me-5">Ver minhas BP's</a>
            <div class="d-flex">
                <a href="sair.php" class="btn btn-danger me-5">Sair</a>
            </div>
        </div>
    </nav>

    <!-- Saudação ao usuário -->
    <h1>Solicitações de Alteração</h1>

    <!-- Tabela de Solicitações -->
    <?php if (!empty($solicitacoes)): ?>
        <div class="container">
            <table class="solicitacoes-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item da BP</th>
                        <th>Descrição</th>
                        <th>Data Solicitação</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitacoes as $solicitacao): ?>
                        <tr>
                            <td><?= $solicitacao['id']; ?></td>
                            <td><?= $solicitacao['bp_descricao']; ?></td>
                            <td><?= $solicitacao['descricao']; ?></td>
                            <td><?= $solicitacao['data_solicitacao']; ?></td>
                            <td><?= $solicitacao['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="no-results">Nenhuma solicitação encontrada.</p>
    <?php endif; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
