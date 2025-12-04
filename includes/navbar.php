<link rel="stylesheet" href="../css/navbar.css">

<nav class="navbar">
  <div class="nav-container">

    <!-- ===== Logo ===== -->
    <div class="nav-left">
      <img src="../includes/img/logo_nome_arualbp.png" alt="Logo" class="nav-logo">
    </div>

    <!-- ===== Links ===== -->
    <ul class="nav-links" id="navLinks">
      <?php 
      $paginaAtual = basename($_SERVER['PHP_SELF']);

      if ($_SESSION['nivel_acesso'] === 'admin'): ?>
        <li class="<?= $paginaAtual == 'home_admin.php' ? 'active' : '' ?>"><a href="../pages/home_admin.php">Painel</a></li>
        <li class="<?= $paginaAtual == 'cadastro_bp.php' ? 'active' : '' ?>"><a href="../pages/cadastro_bp.php">Inventários</a></li>
        <li class="<?= $paginaAtual == 'cadastro_categoria.php' ? 'active' : '' ?>"><a href="../pages/cadastro_categoria.php">Categorias</a></li>
        <li class="<?= $paginaAtual == 'cadastro_unidade.php' ? 'active' : '' ?>"><a href="../pages/cadastro_unidade.php">Unidades</a></li>
        <li class="<?= $paginaAtual == 'cadastro_usuario.php' ? 'active' : '' ?>"><a href="../pages/cadastro_usuario.php">Usuários</a></li>
        <li class="<?= $paginaAtual == 'cadastro_setor.php' ? 'active' : '' ?>"><a href="../pages/cadastro_setor.php">Setores</a></li>
        <li class="<?= $paginaAtual == 'gerenciar_solicitacoes.php' ? 'active' : '' ?>"><a href="../pages/gerenciar_solicitacoes.php">Solicitações</a></li>
        <li class="<?= $paginaAtual == 'relatorios.php' ? 'active' : '' ?>"><a href="../pages/relatorios.php">Relatórios</a></li>

      <?php elseif ($_SESSION['nivel_acesso'] === 'gerente'): ?>
        <li class="<?= $paginaAtual == 'home_gerente.php' ? 'active' : '' ?>"><a href="../pages/home_gerente.php">Painel</a></li>
        <li class="<?= $paginaAtual == 'cadastro_bp.php' ? 'active' : '' ?>"><a href="../pages/cadastro_bp.php">Inventários</a></li>
        <li class="<?= $paginaAtual == 'cadastro_categoria.php' ? 'active' : '' ?>"><a href="../pages/cadastro_categoria.php">Categorias</a></li>
        <li class="<?= $paginaAtual == 'gerenciar_solicitacoes.php' ? 'active' : '' ?>"><a href="../pages/gerenciar_solicitacoes.php">Solicitações</a></li>
        <li class="<?= $paginaAtual == 'relatorios.php' ? 'active' : '' ?>"><a href="../pages/relatorios.php">Relatórios</a></li>

      <?php elseif ($_SESSION['nivel_acesso'] === 'usuario'): ?>
        <li class="<?= $paginaAtual == 'home_usuario.php' ? 'active' : '' ?>"><a href="../pages/home_usuario.php">Meus Inventários</a></li>
        <li class="<?= $paginaAtual == 'solicitacoes.php' ? 'active' : '' ?>"><a href="../pages/solicitacoes.php">Minhas Solicitações</a></li>
        <li class="<?= $paginaAtual == 'relatorios.php' ? 'active' : '' ?>"><a href="../pages/relatorios.php">Relatórios</a></li>
      <?php endif; ?>
      <div class="nav-indicator"></div>
    </ul>

    <!-- ===== Usuário e botão sair ===== -->
    <div class="nav-right">
      <?php
        $nomeCompleto = isset($_SESSION['nome']) ? trim($_SESSION['nome']) : 'Usuário';
        $primeiroNome = explode(' ', $nomeCompleto)[0];
      ?>
      <span class="welcome-text">Bem-vindo(a), <b><?= htmlspecialchars($primeiroNome) ?></b></span>

      <a href="../pages/sair.php" class="btn-sair-modern">
        <span>Sair</span>
      </a>
    </div>

    <!-- ===== Botão Hamburguer (mobile) ===== -->
    <div class="menu-toggle" id="menuToggle">
      <span></span>
      <span></span>
      <span></span>
    </div>

  </div>
</nav>

<script src="../js/navbar.js"></script>
