<?php
include_once('../includes/config.php');
session_start();

if (!isset($_SESSION['id']) || !in_array($_SESSION['nivel_acesso'], ['gerente', 'admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // Verifica vínculos com BP
    $check = $conexao->prepare("SELECT COUNT(*) AS total FROM bps WHERE categoria_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        echo "<script>
                alert('Não é possível excluir esta categoria, pois existem itens de BP vinculados a ela.');
                window.location.href = '../pages/cadastro_categoria.php';
              </script>";
        exit;
    }

    // Exclui categoria
    $sql = "DELETE FROM categorias WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header('Location: ../pages/cadastro_categoria.php');
    exit();
} else {
    header('Location: ../pages/cadastro_categoria.php');
    exit();
}
?>
