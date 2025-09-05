<?php
session_start();    
if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    include_once('config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email' and senha = '$senha'";
    $result = $conexao->query($sql);

    if (mysqli_num_rows($result) < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');
    } else {
        $user_data = $result->fetch_assoc();
        $_SESSION['id'] = $user_data['id']; // Aqui, 'id' é o ID do usuário no banco de dados
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;
        $_SESSION['nivel_acesso'] = $user_data['nivel_acesso']; // Armazena o nível de acesso na sessão

        // Redireciona com base no nível de acesso
        if ($user_data['nivel_acesso'] == 'admin') {
            header('Location: sistema.php');
        } else if ($user_data['nivel_acesso'] == 'usuario') {
            header('Location: sistema_usuario.php');
        } else {
            // Caso tenha um nível de acesso inesperado, redireciona para uma página de erro ou login
            header('Location: login.php');
        }
    }
} else {
    // Não acessa
    header('Location: login.php');
}
?>
