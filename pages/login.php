<?php include '../includes/header.php'; ?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ArualBP</title>
  <link rel="stylesheet" href="../css/login.css">
</head>

<body>
  <canvas id="bubbles"></canvas>

  <div class="login-wrapper">
    <div class="login-card">
      <!-- Lado da imagem -->
      <div class="image-side">
        <img src="../includes/img/ilustracao_login.png" alt="Ilustração ArualBP">
      </div>

      <!-- Lado do formulário -->
      <div class="form-side">
        <div class="logo-area">
          <img src="../includes/img/logo_branca.png" alt="Logo ArualBP">
        </div>

        <h2>Bem-vindo(a)</h2>
        <p class="subtitle">Acesse sua conta para continuar</p>

        <?php if (isset($_GET['erro'])): ?>
          <div class="alert">
            <?php
            switch ($_GET['erro']) {
              case 'campos':
                echo "Preencha todos os campos.";
                break;
              case 'usuario':
                echo "Usuário não encontrado.";
                break;
              case 'senha':
                echo "Senha incorreta.";
                break;
              case 'desativado':
                echo "Usuário desativado. Entre em contato com o administrador.";
                break;
              case 'email_invalido':
                echo "O e-mail informado não está vinculado a nenhum usuário. Contate um administrador.";
                break;
              case 'email':
                echo "Informe um e-mail válido.";
                break;

              default:
                echo "Erro interno, tente novamente.";
            }
            ?>
          </div>
        <?php endif; ?>

        <form action="../actions/testLogin.php" method="POST">
          <input type="text" name="usuario" placeholder="Usuário" required>
          <input type="password" name="senha" placeholder="Senha" required>
          <button type="submit" class="btn-login">Entrar</button>
        </form>


        <a href="#" class="forgot-link" onclick="openForgotModal()">Esqueci minha senha</a>
        
        <!-- Modal Esqueci Minha Senha -->
        <div id="forgotModal" class="modal-forgot">
          <div class="modal-content">
            <h3>Redefinir senha</h3>
            <p>Digite o e-mail vinculado à sua conta ArualBP</p>
        
            <form id="forgotForm" method="POST" action="../actions/forgotPassword.php">
              <input type="email" name="email" placeholder="Seu e-mail" required>
              
              <button type="submit" class="btn-login">Enviar link</button>
              <button type="button" class="btn-cancel" onclick="closeForgotModal()">Cancelar</button>
            </form>
          </div>
        </div>


      </div>
    </div>
  </div>

  <script src="../js/login.js"></script>
  <?php include '../includes/footer.php'; ?>
</body>

</html>