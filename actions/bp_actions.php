<?php
include_once('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $setor_id = (int)($_POST['setor_id'] ?? 0);

    // Campos do formulário
    $nome_item          = trim($_POST['nome_item'] ?? '');
    $descricao          = trim($_POST['descricao'] ?? '');
    $marca              = trim($_POST['marca'] ?? '');
    $modelo             = trim($_POST['modelo'] ?? '');
    $categoria_id       = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null;
    $quantidade         = (int)($_POST['quantidade'] ?? 1);
    $local              = trim($_POST['local'] ?? '');
    $fornecedor         = trim($_POST['fornecedor'] ?? '');
    $data_aquisicao     = !empty($_POST['data_aquisicao']) ? $_POST['data_aquisicao'] : null;
    $custo_unitario     = (float)($_POST['custo_unitario'] ?? 0);
    $custo_total        = (float)($_POST['custo_total'] ?? ($quantidade * $custo_unitario));
    $condicao_aquisicao = trim($_POST['condicao_aquisicao'] ?? '');
    $estado_item        = trim($_POST['estado_item'] ?? '');
    $observacoes        = trim($_POST['observacoes'] ?? '');

    /* =========================================================
       ATUALIZAÇÃO
    ============================================================*/
    if ($id) {
        // 1️⃣ Buscar quantidade e código atuais
        $sqlFetch = "SELECT quantidade, codigo_bp, setor_id FROM bps WHERE id = ?";
        $stmtFetch = $conexao->prepare($sqlFetch);
        $stmtFetch->bind_param("i", $id);
        $stmtFetch->execute();
        $resFetch = $stmtFetch->get_result();
        $rowAtual = $resFetch->fetch_assoc();

        if (!$rowAtual) {
            header("Location: ../pages/cadastro_bp.php?setor_id=$setor_id");
            exit();
        }

        $qtd_antiga  = (int)$rowAtual['quantidade'];
        $codigo_base = $rowAtual['codigo_bp'];
        $setor_id_atual = (int)$rowAtual['setor_id'];

        // 2️⃣ Se ainda não há código (legado), gera agora com o CÓDIGO do setor
        if (empty($codigo_base)) {
            $sqlSetor = "SELECT codigo FROM setores WHERE id = ? LIMIT 1";
            $stmtSetor = $conexao->prepare($sqlSetor);
            $stmtSetor->bind_param("i", $setor_id_atual);
            $stmtSetor->execute();
            $rsSetor = $stmtSetor->get_result()->fetch_assoc();

            $codigoSetor = strtoupper($rsSetor['codigo'] ?? 'XXXX');
            $numero = str_pad($id, 4, '0', STR_PAD_LEFT);
            $codigo_base = "BP-" . $codigoSetor . "-" . $numero;

            $stmtUpdCode = $conexao->prepare("UPDATE bps SET codigo_bp = ? WHERE id = ?");
            $stmtUpdCode->bind_param("si", $codigo_base, $id);
            $stmtUpdCode->execute();
        }

        // 3️⃣ Atualiza o BP
        $sqlUp = "UPDATE bps 
                  SET nome_item=?, descricao=?, marca=?, modelo=?, categoria_id=?, quantidade=?, local=?, fornecedor=?, 
                      data_aquisicao=?, custo_unitario=?, custo_total=?, condicao_aquisicao=?, estado_item=?, observacoes=? 
                  WHERE id=?";
        $stmtUp = $conexao->prepare($sqlUp);
        $stmtUp->bind_param(
            "ssssiisssddsssi",
            $nome_item, $descricao, $marca, $modelo,
            $categoria_id, $quantidade, $local, $fornecedor,
            $data_aquisicao, $custo_unitario, $custo_total,
            $condicao_aquisicao, $estado_item, $observacoes, $id
        );
        $stmtUp->execute();

        // 4️⃣ Sincroniza etiquetas se quantidade mudou
        if ($quantidade > $qtd_antiga) {
            // Adiciona novas
            for ($i = $qtd_antiga + 1; $i <= $quantidade; $i++) {
                $codigo_etiqueta = $codigo_base . '-' . $i;
                $stmtEtiq = $conexao->prepare("INSERT INTO etiquetas_bp (bp_id, codigo_etiqueta) VALUES (?, ?)");
                $stmtEtiq->bind_param("is", $id, $codigo_etiqueta);
                $stmtEtiq->execute();
            }
        } elseif ($quantidade < $qtd_antiga) {
            // Remove etiquetas excedentes
            $sqlDel = "DELETE FROM etiquetas_bp 
                       WHERE bp_id = ? 
                         AND codigo_etiqueta LIKE CONCAT(?, '-%')
                         AND CAST(SUBSTRING_INDEX(codigo_etiqueta, '-', -1) AS UNSIGNED) > ?";
            $stmtDel = $conexao->prepare($sqlDel);
            $stmtDel->bind_param("isi", $id, $codigo_base, $quantidade);
            $stmtDel->execute();
        }
    } 
    /* =========================================================
       INSERÇÃO
    ============================================================*/
    else {
        $sqlIns = "INSERT INTO bps 
                   (setor_id, nome_item, descricao, marca, modelo, categoria_id, quantidade, local, fornecedor, 
                    data_aquisicao, custo_unitario, custo_total, condicao_aquisicao, estado_item, observacoes) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtIns = $conexao->prepare($sqlIns);
        $stmtIns->bind_param(
            "issssiisssddsss",
            $setor_id, $nome_item, $descricao, $marca, $modelo,
            $categoria_id, $quantidade, $local, $fornecedor,
            $data_aquisicao, $custo_unitario, $custo_total,
            $condicao_aquisicao, $estado_item, $observacoes
        );

        if ($stmtIns->execute()) {
            $bp_id = $conexao->insert_id;

            // 🔸 Busca o código do setor
            $sqlSetor = "SELECT codigo FROM setores WHERE id = ? LIMIT 1";
            $stmtSetor = $conexao->prepare($sqlSetor);
            $stmtSetor->bind_param("i", $setor_id);
            $stmtSetor->execute();
            $rsSetor = $stmtSetor->get_result()->fetch_assoc();

            $codigoSetor = strtoupper($rsSetor['codigo'] ?? 'XXXX');
            $numero = str_pad($bp_id, 4, '0', STR_PAD_LEFT);
            $codigo_base = "BP-" . $codigoSetor . "-" . $numero;

            $stmtUpdCode = $conexao->prepare("UPDATE bps SET codigo_bp = ? WHERE id = ?");
            $stmtUpdCode->bind_param("si", $codigo_base, $bp_id);
            $stmtUpdCode->execute();

            // 🔸 Cria etiquetas para cada unidade
            for ($i = 1; $i <= $quantidade; $i++) {
                $sufixo = $quantidade > 1 ? '-' . $i : '';
                $codigo_etiqueta = $codigo_base . $sufixo;
                $stmtEtiq = $conexao->prepare("INSERT INTO etiquetas_bp (bp_id, codigo_etiqueta) VALUES (?, ?)");
                $stmtEtiq->bind_param("is", $bp_id, $codigo_etiqueta);
                $stmtEtiq->execute();
            }
        }
    }

    header("Location: ../pages/cadastro_bp.php?setor_id=$setor_id");
    exit();
}

/* =========================================================
   EXCLUSÃO
============================================================*/
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $setor_id = (int)$_GET['setor_id'];

    // 🔹 Remove etiquetas
    $stmtDelEtq = $conexao->prepare("DELETE FROM etiquetas_bp WHERE bp_id = ?");
    $stmtDelEtq->bind_param("i", $delete_id);
    $stmtDelEtq->execute();

    // 🔹 Remove solicitações associadas a este item (bp_id)
    $stmtDelSolic = $conexao->prepare("DELETE FROM solicitacoes WHERE bp_id = ?");
    $stmtDelSolic->bind_param("i", $delete_id);
    $stmtDelSolic->execute();

    // 🔹 Remove o BP
    $stmtDelBP = $conexao->prepare("DELETE FROM bps WHERE id = ?");
    $stmtDelBP->bind_param("i", $delete_id);
    $stmtDelBP->execute();

    header("Location: ../pages/cadastro_bp.php?setor_id=$setor_id");
    exit();
}
?>
