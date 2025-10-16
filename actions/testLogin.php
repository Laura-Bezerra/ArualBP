<?php
// /actions/testLogin.php
session_start();

if (!isset($_POST['submit']) || empty($_POST['usuario']) || empty($_POST['senha'])) {
  header('Location: ../pages/login.php?erro=campos'); exit;
}

require_once('../includes/config.php');

$usuario = trim($_POST['usuario']);
$senha   = $_POST['senha'];

// Busca o usuário (NÃO compara senha no SQL)
$sql  = "SELECT id, usuario, email, senha, nivel_acesso FROM usuarios WHERE usuario = ? LIMIT 1";
$stmt = $conexao->prepare($sql);
if (!$stmt) { header('Location: ../pages/login.php?erro=interno'); exit; }
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
  $user = $result->fetch_assoc();

  if (password_verify($senha, $user['senha'])) {
    // Ok: cria sessão
    $_SESSION['id']           = (int)$user['id'];
    $_SESSION['usuario']      = $user['usuario'];
    $_SESSION['nivel_acesso'] = $user['nivel_acesso'];

    // Redireciona por nível
    switch ($user['nivel_acesso']) {
      case 'admin':
        header('Location: ../pages/home_admin.php');   break;
      case 'gerente':
        // se não tiver home_gerente.php, pode apontar para home_admin.php
        header('Location: ../pages/home_gerente.php'); break;
      default:
        header('Location: ../pages/home_usuario.php'); break;
    }
    exit;
  } else {
    // Senha incorreta
    header('Location: ../pages/login.php?erro=senha'); exit;
  }
} else {
  // Usuário não encontrado
  header('Location: ../pages/login.php?erro=usuario'); exit;
}
