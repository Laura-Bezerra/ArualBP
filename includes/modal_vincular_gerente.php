<div class="modal fade" id="vincularGerenteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/vincular_gerente.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Definir Gerente do Setor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="setor_id" id="vincular_gerente_setor_id">
          <label for="gerente_id">Selecione o gerente:</label>
          <select class="form-select mt-2" name="gerente_id" required>
            <option value="">Selecione</option>
            <?php
              $res = $conexao->query("SELECT id, nome FROM usuarios WHERE nivel_acesso = 'gerente' ORDER BY nome ASC");
              while($u = $res->fetch_assoc()):
            ?>
              <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
