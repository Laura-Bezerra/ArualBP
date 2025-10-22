<?php
include_once('../includes/config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conexao, $_GET['id']);

    // 🔹 Verifica se o usuário é responsável de algum setor
    $sql1 = "SELECT COUNT(*) AS total FROM setores WHERE usuario_id = '$id'";
    $res1 = $conexao->query($sql1);
    $total1 = $res1->fetch_assoc()['total'];

    // 🔹 Verifica se o usuário é gerente de algum setor
    $sql2 = "SELECT COUNT(*) AS total FROM setores WHERE gerente_id = '$id'";
    $res2 = $conexao->query($sql2);
    $total2 = $res2->fetch_assoc()['total'];

    // 🔹 Verifica se o usuário está vinculado em algum setor como membro
    $sql3 = "SELECT COUNT(*) AS total FROM setor_usuarios WHERE usuario_id = '$id'";
    $res3 = $conexao->query($sql3);
    $total3 = $res3->fetch_assoc()['total'];

    // 🔹 Soma os vínculos
    $total_vinculos = $total1 + $total2 + $total3;

    if ($total_vinculos > 0) {
        echo "<script>
                alert('❌ Este usuário possui vínculos com um ou mais setores e não pode ser excluído.');
                window.location.href = '../pages/cadastro_usuario.php';
              </script>";
        exit;
    }

    // 🔹 Caso não haja vínculos, excluir normalmente
    $delete = $conexao->query("DELETE FROM usuarios WHERE id = '$id'");

    if ($delete) {
        echo "<script>
                alert('✅ Usuário excluído com sucesso!');
                window.location.href = '../pages/cadastro_usuario.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Erro ao excluir usuário: " . addslashes($conexao->error) . "');
                window.location.href = '../pages/cadastro_usuario.php';
              </script>";
        exit;
    }

} else {
    header("Location: ../pages/cadastro_usuario.php");
    exit;
}
?>
