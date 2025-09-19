<div class="modal fade" id="modalSolicitacao" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Solicitação de Modificação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="../actions/processar_solicitacao.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="bp_id" id="bp_id">
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descreva a modificação desejada:</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="submit">Enviar Solicitação</button>
                </div>
            </form>
        </div>
    </div>
</div>
