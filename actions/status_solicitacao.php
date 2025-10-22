<?php
session_start();
include_once('../includes/config.php');

if (!isset($_SESSION['nivel_acesso']) || !in_array($_SESSION['nivel_acesso'], ['gerente', 'admin'])) {
    header('Location: ../pages/login.php');
    exit();
}

if (isset($_POST['acao']) && isset($_POST['solicitacao_id'])) {
    $acao = $_POST['acao'];
    $solicitacao_id = intval($_POST['solicitacao_id']);
    $aprovador = $_SESSION['id'];

    // 🔹 Busca os dados da solicitação
    $sqlSolic = "SELECT tipo, bp_id, campo_alterado, novo_valor FROM solicitacoes WHERE id = ?";
    $stmtSolic = $conexao->prepare($sqlSolic);
    $stmtSolic->bind_param("i", $solicitacao_id);
    $stmtSolic->execute();
    $solic = $stmtSolic->get_result()->fetch_assoc();

    if ($solic) {
        $tipo = $solic['tipo'];
        $bp_id = intval($solic['bp_id']);
        $campo = $solic['campo_alterado'];
        $valor = $solic['novo_valor'];

        /* =========================================================
           APROVAR SOLICITAÇÃO
        ========================================================= */
        if ($acao === 'aprovar') {
            $novo_status = 'aprovado';

            // 🟣 Exclusão de item
            if ($tipo === 'exclusao') {
                // Remove etiquetas
                $stmtDelEtq = $conexao->prepare("DELETE FROM etiquetas_bp WHERE bp_id = ?");
                $stmtDelEtq->bind_param("i", $bp_id);
                $stmtDelEtq->execute();

                // Remove o BP (o solicitacao.bp_id será setado como NULL automaticamente)
                $stmtDelBP = $conexao->prepare("DELETE FROM bps WHERE id = ?");
                $stmtDelBP->bind_param("i", $bp_id);
                $stmtDelBP->execute();
            }

            // 🟣 Alteração de campo
            elseif ($tipo === 'alteracao' && !empty($campo)) {
                if ($campo === 'quantidade') {
                    // Recalcula etiquetas e custo total
                    $sqlFetch = "SELECT quantidade, codigo_bp, custo_unitario FROM bps WHERE id = ?";
                    $stmtFetch = $conexao->prepare($sqlFetch);
                    $stmtFetch->bind_param("i", $bp_id);
                    $stmtFetch->execute();
                    $row = $stmtFetch->get_result()->fetch_assoc();

                    if ($row) {
                        $qtd_antiga = (int)$row['quantidade'];
                        $qtd_nova = (int)$valor;
                        $codigo_base = $row['codigo_bp'];
                        $custo_unitario = (float)$row['custo_unitario'];

                        // Atualiza quantidade e custo total
                        $custo_total = $qtd_nova * $custo_unitario;
                        $stmtUpQtd = $conexao->prepare("UPDATE bps SET quantidade = ?, custo_total = ? WHERE id = ?");
                        $stmtUpQtd->bind_param("idi", $qtd_nova, $custo_total, $bp_id);
                        $stmtUpQtd->execute();

                        // Adiciona etiquetas novas
                        if ($qtd_nova > $qtd_antiga) {
                            for ($i = $qtd_antiga + 1; $i <= $qtd_nova; $i++) {
                                $codigo_etiqueta = $codigo_base . '-' . $i;
                                $stmtAdd = $conexao->prepare("INSERT INTO etiquetas_bp (bp_id, codigo_etiqueta) VALUES (?, ?)");
                                $stmtAdd->bind_param("is", $bp_id, $codigo_etiqueta);
                                $stmtAdd->execute();
                            }
                        }
                        // Remove etiquetas excedentes
                        elseif ($qtd_nova < $qtd_antiga) {
                            $sqlDel = "DELETE FROM etiquetas_bp 
                                       WHERE bp_id = ? 
                                       AND codigo_etiqueta LIKE CONCAT(?, '-%')
                                       AND CAST(SUBSTRING_INDEX(codigo_etiqueta, '-', -1) AS UNSIGNED) > ?";
                            $stmtDel = $conexao->prepare($sqlDel);
                            $stmtDel->bind_param("isi", $bp_id, $codigo_base, $qtd_nova);
                            $stmtDel->execute();
                        }
                    }
                } elseif ($campo === 'custo_unitario') {
                    // Atualiza custo e recalcula total
                    $sqlFetch = "SELECT quantidade FROM bps WHERE id = ?";
                    $stmtFetch = $conexao->prepare($sqlFetch);
                    $stmtFetch->bind_param("i", $bp_id);
                    $stmtFetch->execute();
                    $quantidade = $stmtFetch->get_result()->fetch_assoc()['quantidade'];
                    $custo_total = $quantidade * (float)$valor;

                    $stmtUp = $conexao->prepare("UPDATE bps SET custo_unitario = ?, custo_total = ? WHERE id = ?");
                    $stmtUp->bind_param("ddi", $valor, $custo_total, $bp_id);
                    $stmtUp->execute();
                } else {
                    // Atualiza qualquer outro campo
                    $sqlUpdate = "UPDATE bps SET `$campo` = ? WHERE id = ?";
                    $stmtUpdate = $conexao->prepare($sqlUpdate);
                    $stmtUpdate->bind_param("si", $valor, $bp_id);
                    $stmtUpdate->execute();
                }
            }

            // Marca aprovação
            $sqlFinal = "UPDATE solicitacoes 
                         SET status = ?, aprovado_por = ?, data_aprovacao = NOW() 
                         WHERE id = ?";
            $stmtFinal = $conexao->prepare($sqlFinal);
            $stmtFinal->bind_param("sii", $novo_status, $aprovador, $solicitacao_id);
            $stmtFinal->execute();
        }

        /* =========================================================
           RECUSAR SOLICITAÇÃO
        ========================================================= */
        elseif ($acao === 'recusar') {
            $novo_status = 'recusado';
            $sqlRec = "UPDATE solicitacoes 
                       SET status = ?, data_aprovacao = NOW() 
                       WHERE id = ?";
            $stmtRec = $conexao->prepare($sqlRec);
            $stmtRec->bind_param("si", $novo_status, $solicitacao_id);
            $stmtRec->execute();
        }
    }

    header('Location: ../pages/gerenciar_solicitacoes.php?msg=success');
    exit();
} else {
    header('Location: ../pages/gerenciar_solicitacoes.php');
    exit();
}
?>
