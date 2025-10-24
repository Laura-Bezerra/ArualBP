<?php
session_start();
include_once('../includes/config.php');
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['id']) || !in_array($_SESSION['nivel_acesso'], ['gerente', 'admin'])) {
    header('Location: login.php');
    exit();
}


$sql = "SELECT * FROM categorias ORDER BY id DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Categorias</title>
  <link rel="stylesheet" href="../css/cadastro_categoria.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="page-container">
  <div class="form-section">
    <div class="form-header">
      <i class="fa-solid fa-tags icon"></i>
      <h2>Cadastro de Categorias</h2>      
    </div>

    <form action="../actions/cadastro_categoria_actions.php" method="POST" class="modern-form">
      <div class="inputBox">
        <label for="nome">Nome da Categoria</label>
        <input type="text" name="nome" id="nome" placeholder="Ex: Equipamentos de Informática" required>
      </div>

      <button type="submit" name="submit" class="btn-add">
        <i class="fa-solid fa-plus"></i> Adicionar Categoria
      </button>
    </form>
  </div>

  <div class="categorias-container">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($categoria = $result->fetch_assoc()): ?>
        <div class="categoria-card">
          <div class="card-header">
            <h3><?= htmlspecialchars($categoria['nome']); ?></h3>
          </div>

          <div class="card-actions">
            <button class="btn-edit"
                    data-bs-toggle="modal"
                    data-bs-target="#editCategoriaModal"
                    data-id="<?= $categoria['id']; ?>"
                    data-nome="<?= htmlspecialchars($categoria['nome']); ?>">
              <i class="fa-solid fa-pen"></i>
            </button>

            <a href="../actions/delete_categoria.php?id=<?= $categoria['id']; ?>" 
               class="btn-delete"
               onclick="return confirm('Deseja realmente excluir esta categoria?')">
              <i class="fa-solid fa-trash"></i>
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="sem-categorias">Nenhuma categoria cadastrada ainda.</p>
    <?php endif; ?>
  </div>
</div>

<?php include '../includes/modal_categoria.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/cadastro_categoria.js"></script>
</body>
</html>
