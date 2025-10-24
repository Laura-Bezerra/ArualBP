<?php
session_start();
require_once('../includes/config.php');

$nivel = $_SESSION['nivel_acesso'] ?? '';
$usuario_id = $_SESSION['id'] ?? '';

$setores = [];

if ($nivel === 'admin') {
    $sql = "SELECT id, nome FROM setores ORDER BY nome";
}

elseif ($nivel === 'gerente') {
    $sql = "SELECT id, nome FROM setores WHERE gerente_id = $usuario_id ORDER BY nome";
}

else {
    $sql = "
        SELECT s.id, s.nome
        FROM setores s
        INNER JOIN setor_usuarios su ON su.setor_id = s.id
        WHERE su.usuario_id = $usuario_id
        ORDER BY s.nome
    ";
}

$result = $conexao->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $setores[] = [
            'id' => $row['id'],
            'nome' => $row['nome']
        ];
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($setores);
exit;
