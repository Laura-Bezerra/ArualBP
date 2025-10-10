


<!-- Modal de edição de usuário -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/cadastro_usuarios_actions.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Editar Usuário</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-3">
            <label for="edit_nome">Nome Completo</label>
            <input type="text" name="nome" id="edit_nome" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_usuario">Usuário</label>
            <input type="text" name="usuario" id="edit_usuario" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_email">E-mail</label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_senha">Nova Senha (opcional)</label>
            <input type="password" name="senha" id="edit_senha" class="form-control" placeholder="Deixe em branco para manter a atual">
          </div>


          <div class="mb-3">
            <label for="edit_nivel">Nível de Acesso</label>
            <select name="nivel_acesso" id="edit_nivel" class="form-control" required>
              <option value="usuario">Usuário Comum</option>
              <option value="gerente">Gerente</option>
              <option value="admin">Administrador</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" name="update" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>

