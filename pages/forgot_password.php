<?php
require_once(__DIR__ . '/../includes/config.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Recuperar Senha - ArualBP</title>
  <link rel="stylesheet" href="../css/login.css">
</head>

<body>

  <div class="login-wrapper">
    <div class="login-card">

      <div class="form-side">
        <h2>Recuperar Senha</h2>

        <form action="../actions/send_reset.php" method="POST">
          <input type="email" name="email" placeholder="Digite seu e-mail" required>
          <button type="submit" class="btn-login">Enviar link de redefinição</button>
        </form>

        <p style="margin-top:15px;">
          <a href="login.php" style="color:#441281; text-decoration:none;">Voltar ao login</a>
        </p>
      </div>

    </div>
  </div>

</body>

</html>
