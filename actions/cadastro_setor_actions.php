<?php
include_once('../includes/config.php');
session_start();

if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);
    $codigo = strtoupper(trim($_POST['codigo']));
    $unidade_id = $_POST['unidade_id'] ?? null;

    // Gera código automaticamente se estiver vazio
    if (empty($codigo)) {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $codigo = '';
        for ($i = 0; $i < 4; $i++) {
            $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
    }

    // Evita duplicidade de código
    $check = $conexao->prepare("SELECT COUNT(*) AS total FROM setores WHERE codigo = ?");
    $check->bind_param("s", $codigo);
    $check->execute();
    $exists = $check->get_result()->fetch_assoc();

    if ($exists['total'] > 0) {
        echo "<script>
                alert('Já existe um setor com este código!');
                window.location.href = '../pages/cadastro_setor.php';
              </script>";
        exit;
    }

    $sql = "INSERT INTO setores (nome, codigo, unidade_id) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssi", $nome, $codigo, $unidade_id);
    $stmt->execute();

    header('Location: ../pages/cadastro_setor.php');
    exit();
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $codigo = trim($_POST['codigo']);
    $unidade_id = !empty($_POST['unidade_id']) ? intval($_POST['unidade_id']) : null;

    $sql = "UPDATE setores SET nome = ?, unidade_id = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sii", $nome, $unidade_id, $id);

    $stmt->execute();
    header("Location: ../pages/cadastro_setor.php");
    exit();
}

?>
