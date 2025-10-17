<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';

// 🔒 Restrição de acesso: apenas administrador
if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Consulta unidades cadastradas
$sql = "SELECT * FROM unidades ORDER BY id DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Unidades</title>
  <link rel="stylesheet" href="../css/cadastro_unidade.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="page-container">
  <!-- 🟣 Cabeçalho e formulário -->
  <section class="form-section">
    <div class="form-header">
      <i class="fa-solid fa-building-columns icon"></i>
      <h2>Cadastro de Unidades</h2>
      <p>Adicione novas unidades para organizar melhor seus setores</p>
    </div>

    <form action="../actions/cadastro_unidade_actions.php" method="POST" class="modern-form">
      <div class="input-row">
        <div class="inputBox nome">
          <label for="nome">Nome da Unidade</label>
          <input type="text" name="nome" id="nome" placeholder="Ex: Unidade Central" required>
        </div>

        <div class="inputBox sigla">
          <label for="sigla">Sigla (até 4 letras)</label>
          <input type="text" name="sigla" id="sigla" maxlength="4" placeholder="CENT" required>
        </div>
      </div>

      <div class="inputBox descricao">
        <label for="descricao">Descrição (opcional)</label>
        <textarea name="descricao" id="descricao" rows="2" placeholder="Digite uma breve descrição..."></textarea>
      </div>

      <button type="submit" name="submit" class="btn-add">
        <i class="fa-solid fa-plus"></i> Cadastrar Unidade
      </button>
    </form>
  </section>

  <!-- 🟣 Cards das unidades -->
  <section class="unidades-container">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($unidade = $result->fetch_assoc()): ?>
        <div class="unidade-card">
          <div class="card-header">
            <h3>
              <?= htmlspecialchars($unidade['nome']); ?>
              <span class="sigla-text">Código: <?= htmlspecialchars($unidade['sigla']); ?></span>
            </h3>
          </div>

          <div class="card-body">
            <p><?= htmlspecialchars($unidade['descricao']) ?: '<i>Sem descrição</i>'; ?></p>
          </div>

          <div class="card-actions">
            <!-- Editar unidade -->
            <button class="btn-edit"
                    data-bs-toggle="modal"
                    data-bs-target="#editUnidadeModal"
                    data-id="<?= $unidade['id']; ?>"
                    data-nome="<?= htmlspecialchars($unidade['nome']); ?>"
                    data-sigla="<?= htmlspecialchars($unidade['sigla']); ?>"
                    data-descricao="<?= htmlspecialchars($unidade['descricao']); ?>">
              <i class="fa-solid fa-pen"></i>
            </button>

            <!-- Excluir unidade -->
            <a href="../actions/delete_unidade.php?id=<?= $unidade['id']; ?>" 
               class="btn-delete"
               onclick="return confirm('Deseja realmente excluir esta unidade?')">
              <i class="fa-solid fa-trash"></i>
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="sem-unidades">Nenhuma unidade cadastrada ainda.</p>
    <?php endif; ?>
  </section>
</div>

<?php include '../includes/modal_unidade.php'; ?>
<script src="https://kit.fontawesome.com/a2d5b8d7e4.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/cadastro_unidade.js"></script>


</body>
</html>
