<div class="modal fade" id="vincularGerenteModal" tabindex="-1" aria-labelledby="vincularGerenteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/vincular_gerente.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="vincularGerenteModalLabel">Vincular Gerente ao Setor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="setor_id" id="vincular_gerente_setor_id">

          <label for="gerente_id">Selecione o Gerente:</label>
          <select name="gerente_id" id="gerente_id" class="form-select" required>
            <option value="">Selecione um gerente</option>
            <?php
              $sqlGerentes = "SELECT id, nome FROM usuarios WHERE nivel_acesso = 'gerente'";
              $resultGerentes = $conexao->query($sqlGerentes);
              while ($gerente = $resultGerentes->fetch_assoc()):
            ?>
              <option value="<?= $gerente['id']; ?>"><?= htmlspecialchars($gerente['nome']); ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Vincular</button>
        </div>
      </form>
    </div>
  </div>
</div>
