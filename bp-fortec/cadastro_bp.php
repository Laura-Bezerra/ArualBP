<?php
include_once('config.php');

// Verifica se a edição está sendo solicitada e se os dados foram enviados para atualização
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $quantidade = $_POST['quantidade'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $valor_total = $_POST['valor_total'];
    $especificacoes = $_POST['especificacoes_tecnicas'];
    $local = $_POST['local'];
    $setor_id = $_POST['setor_id'];

    // Atualiza o registro existente
    $sqlUpdate = "UPDATE bps SET descricao='$descricao', marca='$marca', modelo='$modelo', quantidade='$quantidade', data_aquisicao='$data_aquisicao', valor_total='$valor_total', especificacoes_tecnicas='$especificacoes', local='$local' WHERE id='$id'";
    if ($conexao->query($sqlUpdate) === TRUE) {
        // Redireciona de volta para a página principal após a atualização
        header("Location: cadastro_bp.php?setor_id=$setor_id");
        exit(); // Evita que o código continue executando
    } else {
        echo "Erro ao atualizar: " . $conexao->error;
    }
} elseif (isset($_POST['descricao']) && empty($_POST['id'])) {
    // Verifica se um novo registro está sendo inserido
    $descricao = $_POST['descricao'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $quantidade = $_POST['quantidade'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $valor_total = $_POST['valor_total'];
    $especificacoes = $_POST['especificacoes_tecnicas'];
    $local = $_POST['local'];
    $setor_id = $_POST['setor_id'];

    $sqlInsert = "INSERT INTO bps (descricao, marca, modelo, quantidade, data_aquisicao, valor_total, especificacoes_tecnicas, local, setor_id) 
                  VALUES ('$descricao', '$marca', '$modelo', '$quantidade', '$data_aquisicao', '$valor_total', '$especificacoes', '$local', '$setor_id')";
    if ($conexao->query($sqlInsert) === TRUE) {
        header("Location: cadastro_bp.php?setor_id=$setor_id");
        exit(); // Evita que o código continue executando
    } else {
        echo "Erro ao inserir: " . $conexao->error;
    }
}

// Exclusão de BP
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Verificar se o ID do BP existe no banco de dados
    $queryDelete = "DELETE FROM bps WHERE id = ?";
    $stmt = $conexao->prepare($queryDelete);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('BP excluído com sucesso!');</script>";
        header("Location: cadastro_bp.php?setor_id=" . $_GET['setor_id']); // Redirecionar de volta após a exclusão
        exit();
    } else {
        echo "<script>alert('Erro ao excluir o BP. Tente novamente!');</script>";
    }
}

// Verifica se o setor foi selecionado
$setor_id = isset($_POST['setor_id']) ? $_POST['setor_id'] : (isset($_GET['setor_id']) ? $_GET['setor_id'] : '');

// Consulta para listar os bens patrimoniais do setor
$sqlBens = "SELECT * FROM bps WHERE setor_id = '$setor_id'";
$resultBens = $conexao->query($sqlBens);

// Consulta para listar setores
$sqlSetores = "SELECT * FROM setores ORDER BY nome ASC";
$resultSetores = $conexao->query($sqlSetores);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de BP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .modal-header, .btn-primary, .btn-success {
            background-color: #007bff;
            color: #fff;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Cadastro e Gerenciamento de BPs</h2>

        <form method="POST" class="form-inline mb-3">
            <label for="setor" class="mr-2">Selecione o Setor:</label>
            <select name="setor_id" id="setor" class="form-control mr-2">
                <?php while ($setor = $resultSetores->fetch_assoc()): ?>
                    <option value="<?= $setor['id'] ?>" <?= $setor['id'] == $setor_id ? 'selected' : '' ?>><?= $setor['nome'] ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <!-- Botão para adicionar novo BP -->
        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Adicionar Novo BP</button>

        <!-- Tabela de BPs -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Quantidade</th>
                    <th>Data de Aquisição</th>
                    <th>Valor Total</th>
                    <th>Local</th>
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
                            <button class="btn btn-success btn-sm editBtn" data-toggle="modal" data-target="#editModal"
                                data-id="<?= $bp['id'] ?>" data-descricao="<?= $bp['descricao'] ?>"
                                data-marca="<?= $bp['marca'] ?>" data-modelo="<?= $bp['modelo'] ?>"
                                data-quantidade="<?= $bp['quantidade'] ?>" data-data_aquisicao="<?= $bp['data_aquisicao'] ?>"
                                data-valor_total="<?= $bp['valor_total'] ?>" data-local="<?= $bp['local'] ?>">
                                Editar
                            </button>
                            <a href="cadastro_bp.php?delete_id=<?= $bp['id'] ?>&setor_id=<?= $setor_id ?>" class="btn btn-danger btn-sm">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Adição -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Adicionar Novo BP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" name="descricao" required>
                        </div>
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" name="marca" required>
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input type="text" class="form-control" name="modelo" required>
                        </div>
                        <div class="form-group">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" required>
                        </div>
                        <div class="form-group">
                            <label for="data_aquisicao">Data de Aquisição</label>
                            <input type="date" class="form-control" name="data_aquisicao" required>
                        </div>
                        <div class="form-group">
                            <label for="valor_total">Valor Total</label>
                            <input type="number" step="0.01" class="form-control" name="valor_total" required>
                        </div>
                        <div class="form-group">
                            <label for="local">Local</label>
                            <input type="text" class="form-control" name="local" required>
                        </div>
                        <input type="hidden" name="setor_id" value="<?= $setor_id ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar BP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" name="descricao" required>
                        </div>
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" name="marca" required>
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input type="text" class="form-control" name="modelo" required>
                        </div>
                        <div class="form-group">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" required>
                        </div>
                        <div class="form-group">
                            <label for="data_aquisicao">Data de Aquisição</label>
                            <input type="date" class="form-control" name="data_aquisicao" required>
                        </div>
                        <div class="form-group">
                            <label for="valor_total">Valor Total</label>
                            <input type="number" step="0.01" class="form-control" name="valor_total" required>
                        </div>
                        <div class="form-group">
                            <label for="local">Local</label>
                            <input type="text" class="form-control" name="local" required>
                        </div>
                        <input type="hidden" name="setor_id" value="<?= $setor_id ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Preenche os campos do modal de edição com os dados do BP
            $('.editBtn').on('click', function() {
                var id = $(this).data('id');
                var descricao = $(this).data('descricao');
                var marca = $(this).data('marca');
                var modelo = $(this).data('modelo');
                var quantidade = $(this).data('quantidade');
                var data_aquisicao = $(this).data('data_aquisicao');
                var valor_total = $(this).data('valor_total');
                var local = $(this).data('local');

                $('#editModal input[name="id"]').val(id);
                $('#editModal input[name="descricao"]').val(descricao);
                $('#editModal input[name="marca"]').val(marca);
                $('#editModal input[name="modelo"]').val(modelo);
                $('#editModal input[name="quantidade"]').val(quantidade);
                $('#editModal input[name="data_aquisicao"]').val(data_aquisicao);
                $('#editModal input[name="valor_total"]').val(valor_total);
                $('#editModal input[name="local"]').val(local);
            });
        });
    </script>
</body>
</html>
