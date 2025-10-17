<!-- Modal de edição de unidade -->
<div class="modal fade" id="editUnidadeModal" tabindex="-1" aria-labelledby="editUnidadeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="../actions/cadastro_unidade_actions.php">
        <div class="modal-header">
          <h5 class="modal-title" id="editUnidadeModalLabel">Editar Unidade</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-3">
            <label for="edit_nome" class="form-label">Nome da Unidade</label>
            <input type="text" class="form-control" name="nome" id="edit_nome" required>
          </div>

          <div class="mb-3">
            <label for="edit_sigla" class="form-label">Sigla</label>
            <input type="text" class="form-control sigla-disabled" id="edit_sigla" name="sigla" readonly>
            <small class="text-muted">A sigla não pode ser alterada.</small>
          </div>

          <div class="mb-3">
            <label for="edit_descricao" class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" id="edit_descricao" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="update" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>
