<!-- Modal Vincular Usuário -->
<div class="modal fade" id="vincularUsuarioModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/vincular_usuario_setor.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Vincular Usuário ao Setor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="setor_id" id="vincular_setor_id">

          <label for="usuario_id">Selecione o usuário:</label>
          <select class="form-select mt-2" name="usuario_id" id="usuario_id" required>
            <option value="">Selecione</option>
            <?php
              $sqlUsuarios = "SELECT id, nome FROM usuarios WHERE nivel_acesso = 'usuario' ORDER BY nome ASC";
              $res = $conexao->query($sqlUsuarios);
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
