<?php
include_once('../includes/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sqlCheck = "SELECT COUNT(*) as total FROM bps WHERE setor_id = '$id'";
    $resultCheck = mysqli_query($conexao, $sqlCheck);
    $rowCheck = mysqli_fetch_assoc($resultCheck);

    if ($rowCheck['total'] > 0) {
        echo "<script>
                alert('Esse usuário possui vínculos em setores e não pode ser excluído!');
                window.location.href = '../pages/cadastro_setor.php';
              </script>";
        exit;
    }
    
    else {
        $sqlDelete = "DELETE FROM setores WHERE id = '$id'";
        $result = mysqli_query($conexao, $sqlDelete);

        if ($result) {
            header("Location: ../pages/cadastro_setor.php");
            exit;
        } else {
            echo "Erro ao deletar setor: " . mysqli_error($conexao);
        }
    }
}
else {
    header("Location: ../pages/cadastro_setor.php");
    exit;
}
?>
