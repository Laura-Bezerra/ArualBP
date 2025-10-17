<?php
include_once('../includes/config.php');
session_start();

// 🔒 Apenas admin pode excluir unidades
if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// 🔍 Verifica se o ID foi passado
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // 🔎 Verifica se há setores vinculados a essa unidade
    $check = $conexao->prepare("
        SELECT COUNT(*) AS total 
        FROM setores 
        WHERE unidade_id = ?
    ");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        // 🚫 Bloqueia exclusão se houver setores vinculados
        echo "<script>
                alert('Não é possível excluir esta unidade pois existem setores vinculados a ela.');
                window.location.href = '../pages/cadastro_unidade.php';
              </script>";
        exit;
    }

    // 🗑️ Se não houver vínculos, permite exclusão
    $sql = "DELETE FROM unidades WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Unidade excluída com sucesso!');
                window.location.href = '../pages/cadastro_unidade.php';
              </script>";
    } else {
        echo "<script>
                alert('Erro ao excluir a unidade.');
                window.location.href = '../pages/cadastro_unidade.php';
              </script>";
    }

} else {
    header('Location: ../pages/cadastro_unidade.php');
    exit();
}
?>
