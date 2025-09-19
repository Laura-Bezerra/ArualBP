<div class="modal fade" id="addSetorModal" tabindex="-1" aria-labelledby="addSetorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../actions/cadastro_setor_actions.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Setor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="nome">Nome do Setor</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="usuario_id">Responsável</label>
                        <select name="usuario_id" class="form-control" required>
                            <option value="">Selecione um usuário</option>
                            <?php
                            $sqlUsuariosModal = "SELECT * FROM usuarios ORDER BY nome ASC";
                            $resultUsuariosModal = $conexao->query($sqlUsuariosModal);
                            while ($usuario = $resultUsuariosModal->fetch_assoc()):
                            ?>
                                <option value="<?= $usuario['id'] ?>"><?= $usuario['nome'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" name="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editSetorModal" tabindex="-1" aria-labelledby="editSetorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../actions/cadastro_setor_actions.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Setor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-2">                        
                        <input type="text" name="nome" id="edit_nome" class="form-control" required>
                    </div>
                    <div class="mb-2">                        
                        <select name="usuario_id" id="edit_usuario" class="form-control" required>
                            <?php
                            $sqlUsuariosModal2 = "SELECT * FROM usuarios ORDER BY nome ASC";
                            $resultUsuariosModal2 = $conexao->query($sqlUsuariosModal2);
                            while ($usuario = $resultUsuariosModal2->fetch_assoc()):
                            ?>
                                <option value="<?= $usuario['id'] ?>"><?= $usuario['nome'] ?></option>
                            <?php endwhile; ?>
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
