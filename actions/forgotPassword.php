<?php
require_once('../includes/config.php');

if (empty($_POST['email'])) {
  header('Location: ../pages/login.php?erro=email'); exit;
}

$email = trim($_POST['email']);

$sql = "SELECT id, nome FROM usuarios WHERE email = ? LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  $token = bin2hex(random_bytes(32));
  $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

  $conexao->query("DELETE FROM password_resets WHERE email = '$email'");
  $stmt2 = $conexao->prepare("INSERT INTO password_resets (email, token, expira) VALUES (?, ?, ?)");
  $stmt2->bind_param("sss", $email, $token, $expira);
  $stmt2->execute();

  $link = "https://seusite.com/pages/reset_password.php?token=$token";

  $assunto = "Redefinição de Senha - ArualBP";
  $mensagem = "Olá, {$user['nome']}!\n\nClique no link abaixo para redefinir sua senha (válido por 1 hora):\n$link";
  $headers = "From: noreply@seusite.com\r\n";

  mail($email, $assunto, $mensagem, $headers);

  header('Location: ../pages/login.php?msg=link_enviado'); exit;
} else {
  header('Location: ../pages/login.php?erro=email_invalido'); exit;
}
