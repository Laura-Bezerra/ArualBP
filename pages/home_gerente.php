<?php 
session_start();
include_once('../includes/config.php');
include '../includes/header.php'; 

if (!isset($_SESSION['usuario']) || $_SESSION['nivel_acesso'] !== 'gerente') {
    session_unset();
    header('Location: login.php');
    exit();
}

$gerente_id = $_SESSION['id'];

// 🔹 Pega todos os setores do gerente
$sqlSetoresGerente = "SELECT id FROM setores WHERE gerente_id = $gerente_id";
$resultSetores = $conexao->query($sqlSetoresGerente);

$setores_ids = [];
while ($row = $resultSetores->fetch_assoc()) {
    $setores_ids[] = $row['id'];
}
$ids_str = implode(',', $setores_ids ?: [0]); // evita erro se não houver setor

/* ====== CARDS DE RESUMO ====== */

// Total de bens patrimoniais (soma das quantidades)
$totalBPs = $conexao->query("
    SELECT COALESCE(SUM(quantidade), 0) AS total
    FROM bps
    WHERE setor_id IN ($ids_str)
")->fetch_assoc()['total'];

// Total de setores
$totalSetores = count($setores_ids);

// Total de usuários (usuários vinculados aos setores do gerente)
$totalUsuarios = $conexao->query("
    SELECT COUNT(DISTINCT su.usuario_id) AS total
    FROM setor_usuarios su
    WHERE su.setor_id IN ($ids_str)
")->fetch_assoc()['total'];

// Solicitações pendentes
$totalSolicPend = $conexao->query("
    SELECT COUNT(*) AS total
    FROM solicitacoes
    WHERE status = 'pendente' AND setor_id IN ($ids_str)
")->fetch_assoc()['total'];

// Valor total dos itens
$totalValor = $conexao->query("
    SELECT COALESCE(SUM(custo_total), 0) AS total
    FROM bps
    WHERE setor_id IN ($ids_str)
")->fetch_assoc()['total'];

/* ====== Últimas solicitações ====== */
$sqlSolic = "
    SELECT s.id, u.nome AS usuario, se.nome AS setor, s.status, s.data_solicitacao
    FROM solicitacoes s
    LEFT JOIN usuarios u ON u.id = s.usuario_id
    LEFT JOIN setores se ON se.id = s.setor_id
    WHERE s.setor_id IN ($ids_str)
    ORDER BY s.data_solicitacao DESC
    LIMIT 5
";
$resultSolic = $conexao->query($sqlSolic);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>ArualBP - Painel do Gerente</title>
  <link rel="stylesheet" href="../css/home_admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php include '../includes/navbar.php'; ?>
</head>

<body>
  <div class="container-dashboard">

    <!-- ===== CARDS DE RESUMO ===== -->
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
          <p>Setores Gerenciados</p>
        </div>
      </div>

      <div class="card usuarios">
        <div class="card-content">
          <h3><?= $totalUsuarios ?></h3>
          <p>Usuários Vinculados</p>
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

    <!-- ===== GRÁFICOS ===== -->
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

    <!-- ===== ATALHOS RÁPIDOS ===== -->
    <div class="quick-actions">
      <a href="cadastro_bp.php" class="action-card"><i class="fa-solid fa-boxes-stacked"></i><span>Cadastro BP</span></a>
      <a href="gerenciar_solicitacoes.php" class="action-card"><i class="fa-solid fa-list-check"></i><span>Gerenciar Solicitações</span></a>
      <a href="relatorios.php" class="action-card"><i class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
    </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
  <script src="../js/home_gerente.js"></script>
</body>
</html>
