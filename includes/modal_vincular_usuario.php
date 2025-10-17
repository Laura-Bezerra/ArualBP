<div class="modal fade" id="vincularUsuarioModal" tabindex="-1" aria-labelledby="vincularUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/vincular_usuario_setor.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Vincular Usuário ao Setor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="setor_id" id="vincular_usuario_setor_id">

          <label for="usuario_id">Selecione o Usuário:</label>
          <select name="usuario_id" id="usuario_id" class="form-select" required>
            <option value="">Selecione um usuário</option>
            <?php
              $sqlUsuarios = "SELECT id, nome FROM usuarios WHERE nivel_acesso = 'usuario'";
              $resultUsuarios = $conexao->query($sqlUsuarios);
              while ($usuario = $resultUsuarios->fetch_assoc()):
            ?>
              <option value="<?= $usuario['id']; ?>"><?= htmlspecialchars($usuario['nome']); ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Vincular</button>
        </div>
      </form>
    </div>
  </div>
</div>
