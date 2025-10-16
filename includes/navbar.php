<link rel="stylesheet" href="../css/navbar.css">
<nav class="navbar navbar-expand custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">CONTROLE BP's</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto"> 
                <?php if ($_SESSION['nivel_acesso'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/home_admin.php">Início</a>
                    </li>                    
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/cadastro_bp.php">Cadastro BP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/cadastro_usuario.php">Cadastro Usuário</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/cadastro_setor.php">Cadastro Setor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/gerenciar_solicitacoes.php">Gerenciar Solicitações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/relatorios.php">Relatórios</a>
                    </li>
                
                <?php elseif ($_SESSION['nivel_acesso'] === 'gerente'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/home_gerente.php">Início</a>
                    </li>                    
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/cadastro_bp.php">Cadastro BP</a>
                    </li>                    
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/gerenciar_solicitacoes.php">Gerenciar Solicitações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/relatorios.php">Relatórios</a>
                    </li>

                <?php elseif ($_SESSION['nivel_acesso'] === 'usuario'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/home_usuario.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/solicitacoes.php">Minhas Solicitações</a>
                    </li>
                <?php endif; ?>
            </ul>
            <a href="../pages/sair.php" class="btn btn-danger me-5">Sair</a>
        </div>
    </div>
</nav>



