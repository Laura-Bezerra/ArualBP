<?php
include_once('../includes/config.php');
include_once('../includes/flash.php');

// --- Cadastro de novo usuário ---
if (isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
    $nivel_acesso = $_POST['nivel_acesso'];

    // 🔹 Verifica se o nome de usuário já existe
    $check = $conexao->prepare("SELECT COUNT(*) AS total FROM usuarios WHERE usuario = ?");
    $check->bind_param("s", $usuario);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        setFlash('error', "❌ O nome de usuário <strong>$usuario</strong> já está sendo utilizado. Escolha outro.", [
            'nome' => $nome,
            'email' => $email,
            'usuario' => $usuario,
            'nivel_acesso' => $nivel_acesso
        ]);
        header("Location: ../pages/cadastro_usuario.php");
        exit;
    }

    // 🔹 Se não existir, faz o cadastro normalmente
    $sql = "INSERT INTO usuarios (nome, usuario, email, senha, nivel_acesso, ativo) 
            VALUES (?, ?, ?, ?, ?, 1)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssss", $nome, $usuario, $email, $senha, $nivel_acesso);

    if ($stmt->execute()) {
        setFlash('success', "✅ Usuário <strong>$usuario</strong> cadastrado com sucesso!");
    } else {
        setFlash('error', "⚠️ Erro ao cadastrar o usuário.");
    }

    header("Location: ../pages/cadastro_usuario.php");
    exit;
}

// ========== ATUALIZAR USUÁRIO EXISTENTE ==========
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $nivel_acesso = $_POST['nivel_acesso'];
    $senha = $_POST['senha'] ?? '';

    if (!empty($senha)) {
        // Atualiza com nova senha (criptografada)
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios 
                SET nome=?, usuario=?, email=?, senha=?, nivel_acesso=? 
                WHERE id=?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssi", $nome, $usuario, $email, $senha_hash, $nivel_acesso, $id);
    } else {
        // Atualiza sem alterar senha
        $sql = "UPDATE usuarios 
                SET nome=?, usuario=?, email=?, nivel_acesso=? 
                WHERE id=?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $usuario, $email, $nivel_acesso, $id);
    }

    if ($stmt->execute()) {
        header('Location: ../pages/cadastro_usuario.php?status=updated');
        exit;
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
}

// Ativar/Desativar usuário
if (isset($_GET['toggle_id'])) {
    $id = intval($_GET['toggle_id']);

    // Pega o status atual
    $sqlStatus = "SELECT ativo FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($sqlStatus);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $novoStatus = $result['ativo'] ? 0 : 1;

    // Atualiza
    $sqlUpdate = "UPDATE usuarios SET ativo = ? WHERE id = ?";
    $stmt2 = $conexao->prepare($sqlUpdate);
    $stmt2->bind_param("ii", $novoStatus, $id);
    $stmt2->execute();

    header('Location: ../pages/cadastro_usuario.php');
    exit;
}

?>
