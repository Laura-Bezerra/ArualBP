<!-- Modal Adicionar Setor -->
<div class="modal fade" id="addSetorModal" tabindex="-1" aria-labelledby="addSetorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/cadastro_setor_actions.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Adicionar Setor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nome">Nome do Setor</label>
            <input type="text" name="nome" class="form-control" required placeholder="Digite o nome do setor">
          </div>
          <p class="text-muted small mb-0">
            🔸 O responsável pode ser vinculado depois.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" name="submit" class="btn btn-success">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Editar Setor -->
<div class="modal fade" id="editSetorModal" tabindex="-1" aria-labelledby="editSetorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/cadastro_setor_actions.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="editSetorModalLabel">Editar Setor</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">

          <div class="mb-3">
            <label for="edit_nome" class="form-label">Nome do Setor</label>
            <input type="text" id="edit_nome" name="nome" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_codigo" class="form-label">Código</label>
            <input type="text" id="edit_codigo" name="codigo" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label for="edit_unidade" class="form-label">Unidade Vinculada</label>
            <select id="edit_unidade" name="unidade_id" class="form-control">
              <option value="">— Nenhuma unidade —</option>
              <?php
              $sqlUnidadesModal = "SELECT id, nome, sigla FROM unidades ORDER BY nome ASC";
              $resUnidadesModal = $conexao->query($sqlUnidadesModal);
              while ($u = $resUnidadesModal->fetch_assoc()):
              ?>
                <option value="<?= $u['id']; ?>">
                  <?= htmlspecialchars($u['nome']); ?> (<?= htmlspecialchars($u['sigla']); ?>)
                </option>
              <?php endwhile; ?>
            </select>
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


