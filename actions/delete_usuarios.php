<?php
include_once('../includes/config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conexao, $_GET['id']);

    $verificaSetor = $conexao->query("SELECT COUNT(*) AS total FROM setores WHERE usuario_id = '$id'");
    $row = $verificaSetor->fetch_assoc();

    if ($row['total'] > 0) {
        echo "<script>
                alert('Esse usuário possui vínculos em setores e não pode ser excluído!');
                window.location.href = '../pages/cadastro_usuario.php';
              </script>";
        exit;
    }

    // Se não houver vínculos, deleta o usuário
    $delete = $conexao->query("DELETE FROM usuarios WHERE id = '$id'");
    if ($delete) {
        header("Location: ../pages/cadastro_usuario.php");
        exit;
    } else {
        echo "Erro ao excluir usuário: " . $conexao->error;
    }

} else {
    header("Location: ../pages/cadastro_usuario.php");
    exit;
}
?>
