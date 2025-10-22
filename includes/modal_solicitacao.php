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

                <!-- Tipo de solicitação -->
                <div class="mb-3">
                    <label class="form-label">Tipo de solicitação:</label>
                    <select class="form-select" id="tipo_solicitacao" name="tipo_solicitacao" required>
                        <option value="">Selecione...</option>
                        <option value="alteracao">Solicitar Alteração</option>
                        <option value="exclusao">Desejo Excluir o Item</option>
                    </select>
                </div>

                <!-- Campos visíveis apenas se for alteração -->
                <div id="campos-alteracao">
                    <div class="mb-3">
                        <label for="campo_alterar" class="form-label">Campo a alterar:</label>
                        <select class="form-select" id="campo_alterar" name="campo_alterar">
                            <option value="">Selecione...</option>
                            <option value="nome_item">Nome do Item</option>
                            <option value="descricao">Descrição</option>
                            <option value="marca">Marca</option>
                            <option value="modelo">Modelo</option>
                            <option value="quantidade">Quantidade</option>
                            <option value="local">Localização</option>
                            <option value="fornecedor">Fornecedor</option>
                            <option value="custo_unitario">Custo por Unidade</option>
                            <option value="custo_total">Custo Total</option>
                            <option value="condicao_aquisicao">Condição (Aquisição)</option>
                            <option value="estado_item">Estado do Item</option>
                            <option value="observacoes">Observações</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="valor_atual" class="form-label">Valor atual:</label>
                        <input type="text" class="form-control" id="valor_atual" name="valor_atual" readonly>
                    </div>

                    <div class="mb-3" id="campo-novo-valor-container">
                        <label for="novo_valor" class="form-label">Novo valor desejado:</label>
                        <input type="text" class="form-control" id="novo_valor" name="novo_valor">
                    </div>
                </div>

                <!-- Motivo (sempre aparece) -->
                <div class="mb-3">
                    <label for="descricao" class="form-label">Motivo da solicitação:</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
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
