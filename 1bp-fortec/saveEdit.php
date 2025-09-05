<?php
    include_once('config.php');
    if(isset($_POST['update']))
    {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $nivel_acesso = $_POST['nivel_acesso'];
        
        $sqlInsert = "UPDATE usuarios 
        SET nome='$nome',senha='$senha',email='$email',nivel_acesso='$nivel_acesso'
        WHERE id=$id";
        $result = $conexao->query($sqlInsert);
        print_r($result);
    }
    header('Location: cadastro_usuario.php');

?>