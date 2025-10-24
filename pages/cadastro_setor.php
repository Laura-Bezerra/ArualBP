<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';

// 🔒 Acesso restrito a admin e gerente
if (!isset($_SESSION['id']) || ($_SESSION['nivel_acesso'] !== 'admin' && $_SESSION['nivel_acesso'] !== 'gerente')) {
  header('Location: ../login.php');
  exit();
}

// 🔹 Busca setores com unidade associada
$sqlSetores = "
  SELECT s.*, u.nome AS unidade_nome, u.sigla AS unidade_sigla
  FROM setores s
  LEFT JOIN unidades u ON s.unidade_id = u.id
  ORDER BY s.id DESC
";
$resultSetores = $conexao->query($sqlSetores);

// 🔹 Busca unidades disponíveis
$sqlUnidades = "SELECT id, nome, sigla FROM unidades ORDER BY nome ASC";
$resultUnidades = $conexao->query($sqlUnidades);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Cadastro de Setores</title>
  <link rel="stylesheet" href="../css/cadastro_setor.css">
  <link rel="stylesheet" href="../css/modal_setor.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

  <div class="page-container">
    <h2>Cadastro de Setores</h2>

    <!-- 🟣 Formulário de cadastro -->
    <form action="../actions/cadastro_setor_actions.php" method="POST" class="form-cadastro">
      <div class="form-card">
        <div class="form-title">
        </div>

        <div class="form-row">
          <div class="inputBox">
            <label for="nome">Nome do Setor</label>
            <input type="text" name="nome" id="nome" placeholder="Ex: Recursos Humanos" required>
          </div>

          <div class="inputBox small">
            <label for="codigo">Código (até 4 letras)</label>
            <input type="text" name="codigo" id="codigo" maxlength="4" placeholder="RH01">
          </div>
        </div>

        <div class="inputBox">
          <label for="unidade_id">Unidade</label>
          <select name="unidade_id" id="unidade_id" required>
            <option value="">Selecione a Unidade</option>
            <?php while ($unidade = $resultUnidades->fetch_assoc()): ?>
              <option value="<?= $unidade['id'] ?>">
                <?= htmlspecialchars($unidade['nome']) ?> (<?= htmlspecialchars($unidade['sigla']) ?>)
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <button type="submit" name="submit" class="btn-add">
          <i class="fa-solid fa-plus"></i> Cadastrar Setor
        </button>
      </div>
    </form>


    <!-- 🔹 Lista de setores em cards -->
    <div class="setores-container">
      <?php if ($resultSetores->num_rows > 0): ?>
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
              <span class="codigo">Cód: <?= htmlspecialchars($setor['codigo'] ?: '---') ?></span>
            </div>
            <div class="card-body">
              <p><strong>Unidade:</strong> <?= htmlspecialchars($setor['unidade_nome'] ?: 'Sem unidade') ?></p>
              <p><strong>Gerente:</strong> <?= htmlspecialchars($gerenteNome) ?>
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
              <!-- Editar setor -->
              <button class="btn-icon editSetorBtn"
                data-bs-toggle="modal"
                data-bs-target="#editSetorModal"
                data-id="<?= $setor['id'] ?>"
                data-nome="<?= htmlspecialchars($setor['nome']) ?>"
                data-codigo="<?= htmlspecialchars($setor['codigo']) ?>"
                data-unidade="<?= htmlspecialchars($setor['unidade_id']) ?>">
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

              <!-- Excluir -->
              <a href="../actions/delete_setor.php?id=<?= $setor['id'] ?>"
                class="btn-icon deleteBtn"
                onclick="return confirm('Tem certeza que deseja excluir este setor?')">
                <i class="fa-solid fa-trash"></i>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-muted mt-3">Nenhum setor cadastrado ainda.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- 🔹 Modais -->
  <?php include '../includes/modal_setor.php'; ?>
  <?php include '../includes/modal_vincular_gerente.php'; ?>
  <?php include '../includes/modal_vincular_usuario.php'; ?>

  <script src="../js/cadastro_setor.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>