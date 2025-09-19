<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';


if ($_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$sql = "SELECT s.id, u.usuario, s.descricao, s.status, s.data_solicitacao, st.nome AS setor 
        FROM solicitacoes s 
        JOIN usuarios u ON s.usuario_id = u.id 
        JOIN setores st ON s.setor_id = st.id";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<link rel="stylesheet" href="../css/gerenciar_solicitacoes.css">
</head>
<body>
<div class = "conteudo">
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
                    <td><?= $row['usuario']; ?></td>
                    <td><?= $row['setor']; ?></td>
                    <td><?= $row['descricao']; ?></td>
                    <td><?= $row['status']; ?></td>
                    <td class="acoes">
                        <?php if ($row['status'] === 'pendente'): ?>
                            <form action="../actions/status_solicitacao.php" method="post">
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

</div>
</body>
</html>
