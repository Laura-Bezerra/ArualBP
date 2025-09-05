<!-- Modal Adicionar BP -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="../actions/bp_actions.php">
        <div class="modal-header">
          <h5 class="modal-title" id="addModalLabel">Adicionar Novo BP</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="setor_id" value="<?= $setor_id ?>">

          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <input type="text" name="descricao" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" name="quantidade" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Data de Aquisição</label>
            <input type="date" name="data_aquisicao" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Valor Total</label>
            <input type="number" step="0.01" name="valor_total" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Especificações Técnicas</label>
            <textarea name="especificacoes_tecnicas" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Local</label>
            <input type="text" name="local" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar BP -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="../actions/bp_actions.php">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Editar BP</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="setor_id" value="<?= $setor_id ?>">

          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <input type="text" name="descricao" id="edit-descricao" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" id="edit-marca" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" id="edit-modelo" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" name="quantidade" id="edit-quantidade" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Data de Aquisição</label>
            <input type="date" name="data_aquisicao" id="edit-data_aquisicao" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Valor Total</label>
            <input type="number" step="0.01" name="valor_total" id="edit-valor_total" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Especificações Técnicas</label>
            <textarea name="especificacoes_tecnicas" id="edit-especificacoes" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Local</label>
            <input type="text" name="local" id="edit-local" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Atualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
