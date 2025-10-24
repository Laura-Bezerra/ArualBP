<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['nivel_acesso'] !== 'admin') {
    session_unset();
    header('Location: login.php');
    exit();
}

$totalBPs = $conexao->query("SELECT SUM(quantidade) AS total FROM bps")->fetch_assoc()['total'] ?? 0;
$totalSetores = $conexao->query("SELECT COUNT(*) AS total FROM setores")->fetch_assoc()['total'];
$totalUsuarios = $conexao->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$totalSolicPend = $conexao->query("SELECT COUNT(*) AS total FROM solicitacoes WHERE status='pendente'")->fetch_assoc()['total'];
$totalValor = $conexao->query("SELECT SUM(custo_total) AS total FROM bps")->fetch_assoc()['total'] ?? 0;

$sqlSolic = "SELECT s.id, u.nome AS usuario, se.nome AS setor, s.status, s.data_solicitacao
             FROM solicitacoes s
             LEFT JOIN usuarios u ON u.id = s.usuario_id
             LEFT JOIN setores se ON se.id = s.setor_id
             ORDER BY s.data_solicitacao DESC
             LIMIT 5";
$resultSolic = $conexao->query($sqlSolic);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>ArualBP - Painel Administrativo</title>
  <link rel="stylesheet" href="../css/home_admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php include '../includes/navbar.php'; ?>
</head>

<body>
  <div class="container-dashboard">

    <div class="cards-grid">
        <div class="card bp">
            <div class="card-content">
            <h3><?= $totalBPs ?></h3>
            <p>Bens Patrimoniais</p>
            </div>
        </div>

        <div class="card setores">
            <div class="card-content">
            <h3><?= $totalSetores ?></h3>
            <p>Setores</p>
            </div>
        </div>

        <div class="card usuarios">
            <div class="card-content">
            <h3><?= $totalUsuarios ?></h3>
            <p>Usuários</p>
            </div>
        </div>

        <div class="card solicitacoes">
            <div class="card-content">
            <h3><?= $totalSolicPend ?></h3>
            <p>Solicitações Pendentes</p>
            </div>
        </div>

        <div class="card valor">
            <div class="card-content">
            <h3>R$ <?= number_format($totalValor, 2, ',', '.') ?></h3>
            <p>Valor Total</p>
            </div>
        </div>
        </div>

    <!-- ====== GRÁFICOS ====== -->
    <div class="charts-grid">
        <div class="chart-card">
            <h4>BPs por Setor</h4>
            <canvas id="bpsPorSetor"></canvas>        
        </div>

        <div class="chart-card">
            <h4>Estados dos Itens</h4>
            <canvas id="estadoItens"></canvas>
        </div>
    </div>

     <!-- ====== ÚLTIMAS SOLICITAÇÕES ====== -->
                <div class="table-card">
                    <h4>Últimas Solicitações</h4>
                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Setor</th>
                            <th>Status</th>
                            <th>Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($resultSolic->num_rows > 0): ?>
                            <?php while ($row = $resultSolic->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['usuario']) ?></td>
                                <td><?= htmlspecialchars($row['setor']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['data_solicitacao'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center; color:#888;">Nenhuma solicitação recente</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div> 


    <!-- ====== ATALHOS RÁPIDOS ====== -->
    <div class="quick-actions">
      <a href="cadastro_bp.php" class="action-card"><i class="fa-solid fa-boxes-stacked"></i><span>Cadastro BP</span></a>
      <a href="cadastro_usuario.php" class="action-card"><i class="fa-solid fa-user-plus"></i><span>Cadastro Usuário</span></a>
      <a href="cadastro_setor.php" class="action-card"><i class="fa-solid fa-building-user"></i><span>Cadastro Setor</span></a>
      <a href="gerenciar_solicitacoes.php" class="action-card"><i class="fa-solid fa-list-check"></i><span>Gerenciar Solicitações</span></a>
      <a href="relatorios.php" class="action-card"><i class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
    </div>

  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
  <script src="../js/home_admin.js"></script>
</body>
</html>
