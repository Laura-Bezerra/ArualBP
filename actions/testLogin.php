<?php
session_start();    
if (isset($_POST['submit']) && !empty($_POST['usuario']) && !empty($_POST['senha'])) {
    include_once('../includes/config.php');
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' and senha = '$senha'";
    $result = $conexao->query($sql);

    if (mysqli_num_rows($result) < 1) {
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        header('Location: ../pages/login.php');
    } else {
        $user_data = $result->fetch_assoc();
        $_SESSION['id'] = $user_data['id']; 
        $_SESSION['usuario'] = $usuario;
        $_SESSION['senha'] = $senha;
        $_SESSION['nivel_acesso'] = $user_data['nivel_acesso']; 

        
        if ($user_data['nivel_acesso'] == 'admin') {
            header('Location: ../pages/home_admin.php');
        } else if ($user_data['nivel_acesso'] == 'usuario') {
            header('Location: ../pages/home_usuario.php');
        } else {
            header('Location: ../pages/login.php');
        }
    }
} else {
    header('Location: ../pages/login.php');
}
?>
