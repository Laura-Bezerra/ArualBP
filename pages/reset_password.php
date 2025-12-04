<?php
require_once(__DIR__ . '/../includes/config.php');

if (!isset($_GET['token']) && !isset($_POST['token'])) {
    die('Token inválido');
}

$token = $_GET['token'] ?? $_POST['token'];

$sql = "SELECT email, expira FROM password_resets WHERE token = ? LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die('Link inválido ou expirado.');
}

$row = $result->fetch_assoc();
if (strtotime($row['expira']) < time()) {
    die('Link expirado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (strlen($_POST['senha']) < 6) {
        die("A senha deve ter pelo menos 6 caracteres.");
    }

    $novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $email = $row['email'];

    $stmt = $conexao->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    $stmt->bind_param("ss", $novaSenha, $email);
    $stmt->execute();

    $stmt = $conexao->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    echo "<script>alert('Senha redefinida com sucesso!');window.location='../pages/login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Redefinir Senha - ArualBP</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">
      <div class="form-side">
        <h2>Redefinir Senha</h2>

        <form method="POST">
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

          <input type="password" name="senha" placeholder="Nova senha" required minlength="6">
          <button type="submit" class="btn-login">Salvar nova senha</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
