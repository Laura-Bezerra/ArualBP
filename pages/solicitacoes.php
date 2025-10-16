<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php'; 
include '../includes/navbar.php';

// 🔒 Garante que é um usuário comum
if (!isset($_SESSION['id']) || $_SESSION['nivel_acesso'] !== 'usuario') {
    header('Location: ../login.php');
    exit();
}

$usuario_id = $_SESSION['id'];

// 🔹 Busca todas as solicitações de setores em que o usuário participa
$sql = "
    SELECT 
        s.id,
        s.descricao,
        s.data_solicitacao,
        s.status,
        b.descricao AS bp_descricao,
        st.nome AS setor_nome
    FROM solicitacoes s
    JOIN bps b ON s.bp_id = b.id
    JOIN setores st ON s.setor_id = st.id
    JOIN setor_usuarios su ON su.setor_id = s.setor_id
    WHERE su.usuario_id = '$usuario_id'
    ORDER BY s.data_solicitacao DESC
";

$result = $conexao->query($sql);
$solicitacoes = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $solicitacoes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minhas Solicitações</title>
    <link rel="stylesheet" href="../css/solicitacoes.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Minhas Solicitações de Alteração</h1>

    <?php if (!empty($solicitacoes)): ?>
        <table class="solicitacoes-table table table-hover mt-4">
            <thead class="thead-roxo">
                <tr>
                    <th>ID</th>
                    <th>Item da BP</th>
                    <th>Setor</th>
                    <th>Descrição</th>
                    <th>Data da Solicitação</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitacoes as $solicitacao): ?>
                    <tr>
                        <td><?= htmlspecialchars($solicitacao['id']); ?></td>
                        <td><?= htmlspecialchars($solicitacao['bp_descricao']); ?></td>
                        <td><?= htmlspecialchars($solicitacao['setor_nome']); ?></td>
                        <td><?= htmlspecialchars($solicitacao['descricao']); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($solicitacao['data_solicitacao'])); ?></td>
                        <td>
                            <?php
                                $status = $solicitacao['status'];
                                if ($status === 'pendente') {
                                    echo "<span class='badge bg-warning text-dark'>Pendente</span>";
                                } elseif ($status === 'aprovado') {
                                    echo "<span class='badge bg-success'>Aprovado</span>";
                                } elseif ($status === 'recusado') {
                                    echo "<span class='badge bg-danger'>Recusado</span>";
                                } else {
                                    echo "<span class='badge bg-secondary'>-</span>";
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-muted mt-4">Nenhuma solicitação encontrada.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="home_usuario.php" class="btn btn-primary" style="border-radius: 25px; padding: 8px 25px;">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

</body>
</html>