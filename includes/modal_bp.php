
<!-- ================= MODAL ADICIONAR BP ================= -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST" action="../actions/bp_actions.php">
        <div class="modal-header">
          <h5 class="modal-title" id="addModalLabel">Cadastrar Novo Item Patrimonial</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="setor_id" value="<?= $setor_id ?>">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nome do Item</label>
              <input type="text" name="nome_item" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Descrição do Item</label>
              <input type="text" name="descricao" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Marca / Modelo</label>
              <input type="text" name="marca" class="form-control" placeholder="Ex: Dell / Optiplex 7010">
            </div>

            <div class="col-md-6">
              <label class="form-label">Categoria</label>
              <select name="categoria_id" class="form-control" required>
                <option value="">Selecione</option>
                <?php
                  $categorias = $conexao->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
                  while ($cat = $categorias->fetch_assoc()):
                ?>
                  <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label">Quantidade</label>
              <input type="number" name="quantidade" id="quantidade" min="1" value="1" class="form-control" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Custo por Unidade (R$)</label>
              <input type="number" step="0.01" name="custo_unitario" id="custo_unitario" class="form-control" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Custo Total (R$)</label>
              <input type="number" step="0.01" name="custo_total" id="custo_total" class="form-control" readonly>
            </div>

            <div class="col-md-3">
              <label class="form-label">Data de Aquisição</label>
              <input type="date" name="data_aquisicao" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Fornecedor</label>
              <input type="text" name="fornecedor" class="form-control" placeholder="Ex: Dell Computadores Ltda">
            </div>

            <div class="col-md-6">
              <label class="form-label">Localização do Item</label>
              <input type="text" name="local" class="form-control" placeholder="Ex: Laboratório de Informática - Sala 12" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Condição do item quando adquirido</label>
              <select name="condicao_aquisicao" class="form-control" required>
                <option value="">Selecione</option>
                <option value="Novo Funcionando">Novo - Funcionando</option>
                <option value="Usado Funcionando">Usado - Funcionando</option>
                <option value="Usado com defeito">Usado - Com Defeito</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Estado Atual do Item</label>
              <select name="estado_item" class="form-control" required>
                <option value="">Selecione</option>
                <option value="Bom">Bom</option>
                <option value="Apresentando defeitos recorrentes">Apresentando defeitos recorrentes</option>
                <option value="Precisando trocar">Precisando trocar</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Observações</label>
              <textarea name="observacoes" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success" name="submit">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ================= MODAL EDITAR BP ================= -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST" action="../actions/bp_actions.php">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Editar Item Patrimonial</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="setor_id" value="<?= $setor_id ?>">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nome do Item</label>
              <input type="text" name="nome_item" id="edit-nome_item" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Descrição</label>
              <input type="text" name="descricao" id="edit-descricao" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Marca / Modelo</label>
              <input type="text" name="marca" id="edit-marca" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Categoria</label>
              <select name="categoria_id" id="edit-categoria" class="form-control" required>
                <option value="">Selecione</option>
                <?php
                  $categorias2 = $conexao->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
                  while ($cat = $categorias2->fetch_assoc()):
                ?>
                  <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label">Quantidade</label>
              <input type="number" name="quantidade" id="edit-quantidade" min="1" class="form-control" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Custo por Unidade (R$)</label>
              <input type="number" step="0.01" name="custo_unitario" id="edit-custo_unitario" class="form-control">
            </div>

            <div class="col-md-3">
              <label class="form-label">Custo Total (R$)</label>
              <input type="number" step="0.01" name="custo_total" id="edit-custo_total" class="form-control" readonly>
            </div>

            <div class="col-md-3">
              <label class="form-label">Data de Aquisição</label>
              <input type="date" name="data_aquisicao" id="edit-data_aquisicao" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Fornecedor</label>
              <input type="text" name="fornecedor" id="edit-fornecedor" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Localização</label>
              <input type="text" name="local" id="edit-local" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Condição (Aquisição)</label>
              <select name="condicao_aquisicao" id="edit-condicao_aquisicao" class="form-control">
                <option value="">Selecione</option>
                <option value="Novo Funcionando">Novo Funcionando</option>
                <option value="Usado Funcionando">Usado Funcionando</option>
                <option value="Usado com defeito">Usado com Defeito</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Estado Atual</label>
              <select name="estado_item" id="edit-estado_item" class="form-control">
                <option value="">Selecione</option>
                <option value="Bom">Bom</option>
                <option value="Apresentando defeitos recorrentes">Apresentando defeitos recorrentes</option>
                <option value="Precisando trocar">Precisando trocar</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Observações</label>
              <textarea name="observacoes" id="edit-observacoes" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="update" class="btn btn-primary">Atualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../js/modal_bp.js"></script>

