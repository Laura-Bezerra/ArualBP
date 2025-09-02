<?php
session_start();
include('conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Consulta o setor do usuário
$query = "SELECT setor_id FROM usuarios WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$setor = mysqli_fetch_assoc($result);
$setor_id = $setor['setor_id'];

// Consulta os bens patrimoniais do setor
$query_bens = "SELECT * FROM bens_patimoniais WHERE setor_id = $setor_id";
$bens_result = mysqli_query($conn, $query_bens);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Bens Patrimoniais</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Bens Patrimoniais do Seu Setor</h2>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Quantidade</th>
                    <th>Data de Aquisição</th>
                    <th>Valor Total</th>
                    <th>Local</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($bem = mysqli_fetch_assoc($bens_result)) { ?>
                    <tr>
                        <td><?= $bem['descricao'] ?></td>
                        <td><?= $bem['marca'] ?></td>
                        <td><?= $bem['modelo'] ?></td>
                        <td><?= $bem['quantidade'] ?></td>
                        <td><?= $bem['data_aquisicao'] ?></td>
                        <td>R$ <?= number_format($bem['valor_total'], 2, ',', '.') ?></td>
                        <td><?= $bem['local'] ?></td>
                        <td>
                            <!-- Se o usuário for comum, permite enviar uma solicitação para edição -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#solicitacaoModal" data-bem-id="<?= $bem['id'] ?>">Solicitar Edição</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Solicitação de Edição -->
    <div class="modal fade" id="solicitacaoModal" tabindex="-1" aria-labelledby="solicitacaoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="enviar_solicitacao.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="solicitacaoModalLabel">Solicitar Edição do Bem Patrimonial</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="bem_id" id="bem_id">
                        <div class="form-group">
                            <label for="mensagem">Motivo da Solicitação</label>
                            <textarea class="form-control" name="mensagem" id="mensagem" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Enviar Solicitação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preenche o campo hidden com o ID do bem para a solicitação
        $('#solicitacaoModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var bemId = button.data('bem-id');
            var modal = $(this);
            modal.find('#bem_id').val(bemId);
        });
    </script>
</body>
</html>
