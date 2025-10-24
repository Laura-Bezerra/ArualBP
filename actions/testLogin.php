<?php
session_start();

if (empty($_POST['usuario']) || empty($_POST['senha'])) {
  header('Location: ../pages/login.php?erro=campos');
  exit;
}

require_once('../includes/config.php');

$usuario = trim($_POST['usuario']);
$senha   = $_POST['senha'];

$sql  = "SELECT id, nome, usuario, email, senha, nivel_acesso, ativo FROM usuarios WHERE usuario = ? LIMIT 1";
$stmt = $conexao->prepare($sql);
if (!$stmt) {
  header('Location: ../pages/login.php?erro=interno'); 
  exit;
}

$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
  $user = $result->fetch_assoc();

  // 🚫 Verifica se está desativado
  if ((int)$user['ativo'] === 0) {
    header('Location: ../pages/login.php?erro=desativado');
    exit;
  }

  if (password_verify($senha, $user['senha'])) {
    $_SESSION['id']           = (int)$user['id'];
    $_SESSION['usuario']      = $user['usuario'];
    $_SESSION['nivel_acesso'] = $user['nivel_acesso'];
    $_SESSION['nome']         = $user['nome'];

    switch ($user['nivel_acesso']) {
      case 'admin':
        header('Location: ../pages/home_admin.php');   break;
      case 'gerente':
        header('Location: ../pages/home_gerente.php'); break;
      default:
        header('Location: ../pages/home_usuario.php'); break;
    }
    exit;
  } else {
    header('Location: ../pages/login.php?erro=senha');
    exit;
  }
} else {
  header('Location: ../pages/login.php?erro=usuario');
  exit;
}
