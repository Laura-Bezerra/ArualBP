<?php
require_once(__DIR__ . '/../includes/config.php');

// PHPMailer paths
require_once(__DIR__ . '/../includes/phpmailer/src/PHPMailer.php');
require_once(__DIR__ . '/../includes/phpmailer/src/SMTP.php');
require_once(__DIR__ . '/../includes/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acesso inválido.");
}

$email = trim($_POST['email']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<script>alert('E-mail inválido');window.location='../pages/login.php';</script>");
}

$stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Se o e-mail existir, enviaremos um link de redefinição.');window.location='../pages/login.php';</script>";
    exit;
}

$token = bin2hex(random_bytes(32));
$expira = date("Y-m-d H:i:s", time() + 3600);

$stmt = $conexao->prepare("DELETE FROM password_resets WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$stmt = $conexao->prepare("INSERT INTO password_resets (email, token, expira) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $token, $expira);
$stmt->execute();

$link = "https://arualbp.com.br/pages/reset_password.php?token=" . $token;

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';


try {
    $mail->isSMTP();
    $mail->Host = 'HOST SERVIDOR'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'EMAIL';
    $mail->Password = 'SENHA';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('naoresponda@arualbp.com.br', 'ArualBP');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Redefinição de Senha - ArualBP';
    $mail->Body = "
        <h2>Redefinição de Senha</h2>
        <p>Clique no botão abaixo para redefinir sua senha:</p>
        <p><a href='$link' style='background:#441281;color:#fff;padding:10px 18px;border-radius:5px;text-decoration:none;'>Redefinir senha</a></p>
        <p>Se você não solicitou, ignore este e-mail.</p>
    ";

    $mail->send();

} catch (Exception $e) {
    die("Erro ao enviar e-mail: {$mail->ErrorInfo}");
}

echo "<script>alert('Se o e-mail existir, enviaremos um link de redefinição.');window.location='../pages/login.php';</script>";
exit;
