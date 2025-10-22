<?php
session_start();
require_once('../includes/config.php');

$nivel = $_SESSION['nivel_acesso'] ?? '';
$usuario_id = $_SESSION['id'] ?? '';
$setor_id = $_GET['setor_id'] ?? '';

$usuarios = [];

if ($nivel === 'admin') {
    // Admin → todos os usuários ou os de um setor
    if (!empty($setor_id)) {
        $sql = "SELECT DISTINCT u.id, u.nome
                FROM usuarios u
                INNER JOIN setor_usuarios su ON su.usuario_id = u.id
                WHERE su.setor_id = $setor_id
                ORDER BY u.nome";
    } else {
        $sql = "SELECT id, nome FROM usuarios ORDER BY nome";
    }
} elseif ($nivel === 'gerente') {
    // Gerente → apenas usuários dos setores que ele gerencia
    if (!empty($setor_id)) {
        $sql = "
            SELECT DISTINCT u.id, u.nome
            FROM usuarios u
            INNER JOIN setor_usuarios su ON su.usuario_id = u.id
            INNER JOIN setores s ON s.id = su.setor_id
            WHERE s.gerente_id = $usuario_id AND s.id = $setor_id
            ORDER BY u.nome";
    } else {
        $sql = "
            SELECT DISTINCT u.id, u.nome
            FROM usuarios u
            INNER JOIN setor_usuarios su ON su.usuario_id = u.id
            INNER JOIN setores s ON s.id = su.setor_id
            WHERE s.gerente_id = $usuario_id
            ORDER BY u.nome";
    }
} else {
    // Usuário → apenas ele mesmo
    $sql = "SELECT id, nome FROM usuarios WHERE id = $usuario_id";
}

$result = $conexao->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = [
            'id' => $row['id'],
            'nome' => $row['nome']
        ];
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($usuarios);
exit;
