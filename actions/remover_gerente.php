<?php
session_start();
include_once('../includes/config.php');

// 🔒 Restrição: apenas admin ou gerente podem remover
if (!isset($_SESSION['id']) || ($_SESSION['nivel_acesso'] !== 'admin' && $_SESSION['nivel_acesso'] !== 'gerente')) {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['setor_id'])) {
    $setor_id = intval($_GET['setor_id']);

    // 🔹 Primeiro, verificar se o setor tem um gerente
    $sqlGerente = "SELECT gerente_id FROM setores WHERE id = ?";
    $stmtGerente = $conexao->prepare($sqlGerente);
    $stmtGerente->bind_param("i", $setor_id);
    $stmtGerente->execute();
    $resultGerente = $stmtGerente->get_result();

    if ($resultGerente->num_rows > 0) {
        $gerente_id = $resultGerente->fetch_assoc()['gerente_id'];

        if ($gerente_id) {
    // 🔹 Verifica se o gerente possui solicitações vinculadas a ESTE setor específico
            $sqlCheck = "
                SELECT COUNT(*) AS total
                FROM solicitacoes s
                WHERE s.setor_id = ? 
                AND EXISTS (
                    SELECT 1 FROM setores st
                    WHERE st.id = s.setor_id
                    AND st.gerente_id = ?
                )
            ";
            $stmtCheck = $conexao->prepare($sqlCheck);
            $stmtCheck->bind_param("ii", $setor_id, $gerente_id);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            $totalSolic = $resultCheck->fetch_assoc()['total'];

            if ($totalSolic > 0) {
                // ❌ Impede exclusão se houver solicitações vinculadas
                echo "<script>
                    alert('Não é possível remover este gerente, pois ele possui solicitações vinculadas.');
                    window.location.href = '../pages/cadastro_setor.php';
                </script>";
                exit();
            }

            // 🔹 Se não houver solicitações, remover o gerente normalmente
            $sqlRemover = "UPDATE setores SET gerente_id = NULL WHERE id = ?";
            $stmtRemover = $conexao->prepare($sqlRemover);
            $stmtRemover->bind_param("i", $setor_id);

            if ($stmtRemover->execute()) {
                echo "<script>                    
                    window.location.href = '../pages/cadastro_setor.php';
                </script>";
            } else {
                echo "<script>
                    alert('Erro ao remover gerente.');
                    window.location.href = '../pages/cadastro_setor.php';
                </script>";
            }

        } else {
            // Nenhum gerente vinculado
            header('Location: ../pages/cadastro_setor.php');
            exit();
        }

    } else {
        // Setor inexistente
        header('Location: ../pages/cadastro_setor.php');
        exit();
    }

} else {
    header('Location: ../pages/cadastro_setor.php');
    exit();
}
?>
