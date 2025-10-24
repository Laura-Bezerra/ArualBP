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

// ========= FILTROS =========
$filtro_id = $_GET['id'] ?? '';
$filtro_setor = $_GET['setor'] ?? '';
$filtro_status = $_GET['status'] ?? '';
$filtro_data_inicio = $_GET['data_inicio'] ?? '';
$filtro_data_fim = $_GET['data_fim'] ?? '';

// ========= SETORES DO USUÁRIO =========
$sqlSetores = "
    SELECT s.id, s.nome 
    FROM setores s
    JOIN setor_usuarios su ON su.setor_id = s.id
    WHERE su.usuario_id = '$usuario_id'
    ORDER BY s.nome ASC
";
$resultSetores = $conexao->query($sqlSetores);

// ========= CONSULTA PRINCIPAL =========
$sql = "
    SELECT 
        s.id,
        s.descricao,
        s.data_solicitacao,
        s.data_aprovacao,
        s.status,
        s.campo_alterado,
        s.valor_atual,
        s.novo_valor,
        s.tipo,
        b.nome_item AS bp_nome,
        st.nome AS setor_nome
    FROM solicitacoes s
    JOIN bps b ON s.bp_id = b.id
    JOIN setores st ON s.setor_id = st.id
    WHERE s.usuario_id = '$usuario_id'
";

// ===== APLICA FILTROS =====
if (!empty($filtro_id)) {
    $sql .= " AND s.id = " . intval($filtro_id);
}

if (!empty($filtro_setor)) {
    $sql .= " AND st.id = " . intval($filtro_setor);
}

if (!empty($filtro_status)) {
    $sql .= " AND s.status = '" . $conexao->real_escape_string($filtro_status) . "'";
}

if (!empty($filtro_data_inicio) && !empty($filtro_data_fim)) {
    $sql .= " AND DATE(s.data_solicitacao) BETWEEN '$filtro_data_inicio' AND '$filtro_data_fim'";
} elseif (!empty($filtro_data_inicio)) {
    $sql .= " AND DATE(s.data_solicitacao) >= '$filtro_data_inicio'";
} elseif (!empty($filtro_data_fim)) {
    $sql .= " AND DATE(s.data_solicitacao) <= '$filtro_data_fim'";
}

$sql .= " ORDER BY s.data_solicitacao DESC";
$result = $conexao->query($sql);

// ========= ARMAZENA RESULTADOS =========
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Minhas Solicitações</h1>

        <!-- ====== FORMULÁRIO DE FILTROS ====== -->
        <form method="GET" class="row g-3 mb-4 filtros shadow-sm p-3 rounded bg-light">
            <div class="col-md-2">
                <label for="id" class="form-label">ID da Solicitação</label>
                <input type="number" name="id" id="id" value="<?= htmlspecialchars($filtro_id) ?>" class="form-control">
            </div>

            <div class="col-md-3">
                <label for="setor" class="form-label">Setor</label>
                <select name="setor" id="setor" class="form-select">
                    <option value="">Todos</option>
                    <?php while ($setor = $resultSetores->fetch_assoc()): ?>
                        <option value="<?= $setor['id'] ?>" <?= $setor['id'] == $filtro_setor ? 'selected' : '' ?>>
                            <?= htmlspecialchars($setor['nome']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendente" <?= $filtro_status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="aprovado" <?= $filtro_status === 'aprovado' ? 'selected' : '' ?>>Aprovado</option>
                    <option value="recusado" <?= $filtro_status === 'recusado' ? 'selected' : '' ?>>Recusado</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="data_inicio" class="form-label">Data Início</label>
                <input type="date" name="data_inicio" id="data_inicio" value="<?= htmlspecialchars($filtro_data_inicio) ?>" class="form-control">
            </div>

            <div class="col-md-2">
                <label for="data_fim" class="form-label">Data Fim</label>
                <input type="date" name="data_fim" id="data_fim" value="<?= htmlspecialchars($filtro_data_fim) ?>" class="form-control">
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </form>

        <!-- ====== RESULTADOS ====== -->
        <?php if (!empty($solicitacoes)): ?>
            <table class="solicitacoes-table table table-hover mt-4">
                <thead class="thead-roxo">
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                        <th>Setor</th>
                        <th>Tipo</th>
                        <th>Campo Alterado</th>
                        <th>Antes</th>
                        <th>Depois</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Data Solicitação</th>
                        <th>Data Aprovação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitacoes as $s): ?>
                        <tr>
                            <td><?= $s['id'] ?></td>
                            <td><?= htmlspecialchars($s['bp_nome']) ?></td>
                            <td><?= htmlspecialchars($s['setor_nome']) ?></td>
                            <td><?= ucfirst($s['tipo']) ?></td>
                            <td><?= htmlspecialchars($s['campo_alterado'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['valor_atual'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['novo_valor'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['descricao']) ?></td>
                            <td>
                                <?php
                                $status = $s['status'];
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
                            <td><?= date('d/m/Y H:i', strtotime($s['data_solicitacao'])) ?></td>
                            <td>
                                <?= $s['data_aprovacao']
                                    ? date('d/m/Y H:i', strtotime($s['data_aprovacao']))
                                    : '<em class="text-muted">—</em>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-muted mt-4">Nenhuma solicitação encontrada.</p>
        <?php endif; ?>
    </div>

</body>

</html>