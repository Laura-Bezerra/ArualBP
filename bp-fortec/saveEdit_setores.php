<?php
    include_once('config.php');
    if(isset($_POST['update']))
    {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $usuario_id = $_POST['usuario_id'];        
        
        $sqlInsert = "UPDATE setores 
        SET nome='$nome',usuario_id='$usuario_id'
        WHERE id=$id";
        $result = $conexao->query($sqlInsert);
        print_r($result);
    }
    header('Location: cadastro_setor.php');

?>