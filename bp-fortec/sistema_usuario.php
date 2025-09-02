<?php 
session_start();
include_once('config.php');

// Verifica se o usuário está logado e se o nível de acesso é 'usuario'
if ((!isset($_SESSION['email']) || !isset($_SESSION['senha'])) || ($_SESSION['nivel_acesso'] !== 'usuario')) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit();
}

// Armazena o e-mail do usuário logado
$logado = $_SESSION['email'];

// Obter o ID do usuário logado
$sqlUsuario = "SELECT id FROM usuarios WHERE email = '$logado'";
$resultUsuario = $conexao->query($sqlUsuario);
$idUsuario = $resultUsuario->fetch_assoc()['id'];

// Obter setores dos quais o usuário é responsável
$sqlSetores = "SELECT id, nome FROM setores WHERE usuario_id = '$idUsuario'";
$resultSetores = $conexao->query($sqlSetores);

// Verifica se um setor foi selecionado
$itensBP = [];
if (isset($_POST['setor_selecionado'])) {
    $setorSelecionado = $_POST['setor_selecionado'];
    $sqlItensBP = "SELECT id, setor_id, quantidade, descricao, marca, modelo, data_aquisicao, valor_total, especificacoes_tecnicas, local FROM bps WHERE setor_id = '$setorSelecionado'";
    $resultItensBP = $conexao->query($sqlItensBP);

    if ($resultItensBP->num_rows > 0) {
        while ($row = $resultItensBP->fetch_assoc()) {
            $itensBP[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>SISTEMA | GN</title>
    <style>
        /* Estilos Gerais */
        body {
            background-color: #d9d9d9;
            color: #441281;
            margin-top: 80px; /* Espaço para o menu fixo */
            font-family: 'Arial', sans-serif;
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
        .table {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #915ad3;
            color: white;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }

        /* Botões */
        .btn-primary {
            background-color: #f5ad00;
            border: none;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #e59c00;
        }
        .btn-danger {
            background-color: #f5ad00;
            border: none;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #e59c00;
        }

        /* Modal */
        .modal-content {
            background-color: #d9d9d9;
        }
        .modal-header {
            background-color: #441281;
            color: white;
        }

        /* Alinhamento do formulário */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
        }

        .form-container form {
            display: flex;
            gap: 10px; /* Espaço entre o select e o botão */
            align-items: center;
        }

        .form-container select {
            width: auto;
            max-width: 250px; /* Limita a largura do select */
        }

        .form-container button {
            width: auto;
        }
    </style>
</head>
<body>

    <!-- Navbar Fixa -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CONTROLE BP's</a>
            <a href="minhas_solicitacoes.php" class="btn btn-danger me-5">Minhas Solicitações</a>
            <div class="d-flex">
                <a href="sair.php" class="btn btn-danger me-5">Sair</a>
            </div>
        </div>
    </nav>

    <!-- Saudação ao usuário -->
    <h1>Bem-vindo <u><?= $logado; ?></u></h1>

    <!-- Formulário de Seleção de Setor (Alinhado Horizontalmente) -->
    <div class="form-container">
        <form method="post" action="">
            <label for="setor_selecionado">Selecione o setor:</label>
            <select name="setor_selecionado" id="setor_selecionado" class="form-select">
                <?php while ($setor = $resultSetores->fetch_assoc()): ?>
                    <option value="<?= $setor['id']; ?>">
                        <?= $setor['nome']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary">Ver BP</button>
        </form>
    </div>

    <?php if (!empty($itensBP)): ?>
        <!-- Tabela de BP's -->
        <div class="container mt-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Setor ID</th>
                        <th>Quantidade</th>
                        <th>Descrição</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Data de Aquisição</th>
                        <th>Valor Total</th>
                        <th>Especificações Técnicas</th>
                        <th>Local</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itensBP as $item): ?>
                        <tr>
                            <td><?= $item['id']; ?></td>
                            <td><?= $item['setor_id']; ?></td>
                            <td><?= $item['quantidade']; ?></td>
                            <td><?= $item['descricao']; ?></td>
                            <td><?= $item['marca']; ?></td>
                            <td><?= $item['modelo']; ?></td>
                            <td><?= $item['data_aquisicao']; ?></td>
                            <td><?= $item['valor_total']; ?></td>
                            <td><?= $item['especificacoes_tecnicas']; ?></td>
                            <td><?= $item['local']; ?></td>
                            <td>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalSolicitacao" data-bp-id="<?= $item['id']; ?>">Solicitar Modificação</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal para solicitação -->
        <div class="modal fade" id="modalSolicitacao" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Solicitação de Modificação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="processar_solicitacao.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="bp_id" id="bp_id">
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descreva a modificação desejada:</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" name="submit">Enviar Solicitação</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Captura o ID do BP e preenche o campo oculto no modal
            var modalSolicitacao = document.getElementById('modalSolicitacao');
            modalSolicitacao.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var bpId = button.getAttribute('data-bp-id');
                var modalBodyInput = modalSolicitacao.querySelector('#bp_id');
                modalBodyInput.value = bpId;
            });
        </script>
    <?php elseif (isset($_POST['setor_selecionado'])): ?>
        <p>Nenhum item encontrado na BP para o setor selecionado.</p>
    <?php endif; ?>
</body>
</html>
