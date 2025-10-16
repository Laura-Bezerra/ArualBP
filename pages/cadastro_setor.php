<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';

// Busca setores
$sqlSetores = "SELECT * FROM setores ORDER BY id DESC";
$resultSetores = $conexao->query($sqlSetores);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/cadastro_setor.css">
  <link rel="stylesheet" href="../css/modal_setor.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="page-container">
  <h2>Cadastro de Setores</h2>

  <!-- Formulário de cadastro -->
  <form action="../actions/cadastro_setor_actions.php" method="POST" class="form-cadastro">
    <div class="input-group">
      <input type="text" name="nome" id="nome" placeholder="Nome do Setor" required>
      <button type="submit" name="submit" class="btn-add">
        <i class="fa-solid fa-plus"></i> Adicionar
      </button>
    </div>
  </form>

  <!-- Lista de setores -->
  <div class="setores-container">
    <?php while ($setor = $resultSetores->fetch_assoc()): ?>
      <?php
        // Busca gerente
        $gerenteNome = 'Sem gerente definido';
        if (!empty($setor['gerente_id'])) {
          $res = $conexao->query("SELECT nome FROM usuarios WHERE id = " . intval($setor['gerente_id']));
          if ($g = $res->fetch_assoc()) $gerenteNome = $g['nome'];
        }

        // Busca usuários vinculados
        $usuarios = $conexao->query("
          SELECT u.nome 
          FROM setor_usuarios su
          JOIN usuarios u ON su.usuario_id = u.id
          WHERE su.setor_id = {$setor['id']}
        ");
      ?>
      <div class="setor-card">
        <div class="card-header">
          <h3><?= htmlspecialchars($setor['nome']) ?></h3>
        </div>
        <div class="card-body">
            <p>
                <strong>Gerente:</strong> <?= htmlspecialchars($gerenteNome) ?>
                <?php if (!empty($setor['gerente_id'])): ?>
                    <a href="../actions/remover_gerente.php?setor_id=<?= $setor['id'] ?>"
                    class="remove-gerente"
                    title="Remover gerente"
                    onclick="return confirm('Remover o gerente <?= htmlspecialchars($gerenteNome) ?> deste setor?')">
                    <i class="fa-solid fa-user-xmark"></i>
                    </a>
                <?php endif; ?>
            </p>

          <p><strong>Usuários:</strong></p>
          <?php if ($usuarios->num_rows > 0): ?>
            <ul class="user-list">
              <?php while ($u = $usuarios->fetch_assoc()): ?>
                    <li class="user-item">
                        <?= htmlspecialchars($u['nome']) ?>
                        <a href="../actions/remover_usuario_setor.php?setor_id=<?= $setor['id'] ?>&usuario=<?= urlencode($u['nome']) ?>" 
                        class="remove-user" 
                        title="Remover usuário deste setor"
                        onclick="return confirm('Remover <?= htmlspecialchars($u['nome']) ?> deste setor?')">
                        <i class="fa-solid fa-trash"></i>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="text-muted">Nenhum usuário vinculado.</p>
          <?php endif; ?>
        </div>
        <div class="card-actions">
          <!-- Editar nome do setor -->
          <button class="btn-icon editSetorBtn"
                  data-bs-toggle="modal"
                  data-bs-target="#editSetorModal"
                  data-id="<?= $setor['id'] ?>"
                  data-nome="<?= htmlspecialchars($setor['nome']) ?>">
            <i class="fa-solid fa-pen"></i>
          </button>

          <!-- Vincular gerente -->
          <button class="btn-icon linkGerenteBtn"
                  data-bs-toggle="modal"
                  data-bs-target="#vincularGerenteModal"
                  data-id="<?= $setor['id'] ?>">
            <i class="fa-solid fa-user-tie"></i>
          </button>

          <!-- Vincular usuários -->
          <button class="btn-icon linkUserBtn"
                  data-bs-toggle="modal"
                  data-bs-target="#vincularUsuarioModal"
                  data-id="<?= $setor['id'] ?>">
            <i class="fa-solid fa-users"></i>
          </button>

          <!-- Deletar -->
          <a href="../actions/delete_setor.php?id=<?= $setor['id'] ?>" 
             class="btn-icon deleteBtn"
             onclick="return confirm('Tem certeza que deseja excluir este setor?')">
            <i class="fa-solid fa-trash"></i>
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include '../includes/modal_setor.php'; ?>
<?php include '../includes/modal_vincular_gerente.php'; ?>
<?php include '../includes/modal_vincular_usuario.php'; ?>

<script src="../js/cadastro_setor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
