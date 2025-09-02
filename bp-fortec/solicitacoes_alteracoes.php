<?php
session_start();
include_once('config.php');

// Verifica se o usuário é admin
if ($_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$sql = "SELECT s.id, u.email, s.descricao, s.status, s.data_solicitacao, st.nome AS setor 
        FROM solicitacoes s 
        JOIN usuarios u ON s.usuario_id = u.id 
        JOIN setores st ON s.setor_id = st.id";
$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Alterações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo geral */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #d9d9d9;
            color: #441281;
            margin: 0;
            padding-top: 80px; /* Ajusta o espaçamento devido ao menu fixo */
        }

        h1 {
            color: #441281;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #915ad3;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Botões */
        button {
            padding: 8px 16px;
            background-color: #f5ad00;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }

        button:hover {
            background-color: #e59c00;
        }

        /* Estilo para a linha de ações */
        .acoes {
            display: flex;
            gap: 10px;
        }

        .acoes span {
            color: gray;
        }

        /* Botão voltar */
        .voltar-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
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
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CONTROLE BP's</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                        <a class="nav-link" href="sistema.php">Início</a>
                    </li>                    
                <li class="nav-item">
                        <a class="nav-link" href="cadastro_bp.php">Cadastro BP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro_usuario.php">Cadastro Usuário</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro_setor.php">Cadastro Setor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="solicitacoes_alteracoes.php">Solicitação de Alteração</a>
                    </li>
                </ul>
                <a href="sair.php" class="btn btn-danger me-5">Sair</a>
            </div>
        </div>
    </nav>

    <h1>Solicitações de Alterações</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Setor</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['setor']; ?></td>
                    <td><?= $row['descricao']; ?></td>
                    <td><?= $row['status']; ?></td>
                    <td class="acoes">
                        <?php if ($row['status'] === 'pendente'): // Exibe botões apenas se o status for "PENDENTE" ?>
                            <form action="gerenciar_solicitacao.php" method="post">
                                <input type="hidden" name="solicitacao_id" value="<?= $row['id']; ?>">
                                <button name="acao" value="aprovar" type="submit">Aprovar</button>
                                <button name="acao" value="recusar" type="submit">Recusar</button>
                            </form>
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
