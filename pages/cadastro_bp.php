<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';

$setor_id = $_POST['setor_id'] ?? ($_GET['setor_id'] ?? '');


$sqlSetores = "SELECT * FROM setores ORDER BY nome ASC";
$resultSetores = $conexao->query($sqlSetores);

$sqlBens = $setor_id 
    ? "SELECT * FROM bps WHERE setor_id = '$setor_id'" 
    : "SELECT * FROM bps WHERE 1=0";

$resultBens = $conexao->query($sqlBens);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="../css/cadastro_bp.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Cadastro e Gerenciamento de BPs</h2>
        
        <form method="POST" class="form-inline mb-3">
            <label for="setor">Selecione o Setor:</label>
            <select name="setor_id" id="setor" class="form-control">
                <option value="">Selecione o setor</option>
                <?php while ($setor = $resultSetores->fetch_assoc()): ?>
                    <option value="<?= $setor['id'] ?>" <?= $setor['id'] == $setor_id ? 'selected' : '' ?>>
                        <?= $setor['nome'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <?php if (!empty($setor_id)): ?>
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">
                Adicionar Novo BP
            </button>   
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Quantidade</th>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Local</th>
                    <th>Etiqueta</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($bp = $resultBens->fetch_assoc()): ?>
                <tr>
                    <td><?= $bp['id'] ?></td>
                    <td><?= $bp['descricao'] ?></td>
                    <td><?= $bp['marca'] ?></td>
                    <td><?= $bp['modelo'] ?></td>
                    <td><?= $bp['quantidade'] ?></td>
                    <td><?= $bp['data_aquisicao'] ?></td>
                    <td><?= $bp['valor_total'] ?></td>
                    <td><?= $bp['local'] ?></td>                    
                    <td>
                        <button type="button"
                                class="btn btn-link text-decoration-none btn-etiqueta"
                                data-toggle="modal"
                                data-target="#etiquetaModal"
                                data-bp-id="<?= $bp['id'] ?>">
                        <?= $bp['codigo_bp'] ?>
                        </button>
         
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm editBtn" 
                                data-toggle="modal" data-target="#editModal"
                                data-id="<?= $bp['id'] ?>"
                                data-descricao="<?= $bp['descricao'] ?>"
                                data-marca="<?= $bp['marca'] ?>"
                                data-modelo="<?= $bp['modelo'] ?>"
                                data-quantidade="<?= $bp['quantidade'] ?>"
                                data-data_aquisicao="<?= $bp['data_aquisicao'] ?>"
                                data-valor_total="<?= $bp['valor_total'] ?>"
                                data-valor_total="<?= $bp['valor_total'] ?>"
                                data-local="<?= $bp['local'] ?>">
                            Editar
                        </button>
                        <a href="../actions/bp_actions.php?delete_id=<?= $bp['id'] ?>&setor_id=<?= $setor_id ?>" 
                           class="btn btn-danger btn-sm">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/modal_etiqueta.php'; ?>
    <?php include '../includes/modal_bp.php'; ?>
    <script src="../js/cadastro_bp.js"></script>
    <script src="../js/etiquetas.js"></script>
</body>
</html>
