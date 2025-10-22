<!-- Modal de Informações do BP -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="infoModalLabel">
          <i class="fa-solid fa-circle-info me-2"></i>Detalhes do Item
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless align-middle">
          <tbody>
            <tr><th>ID:</th><td id="info_id"></td></tr>
            <tr><th>Código BP:</th><td id="info_codigo_bp"></td></tr>
            <tr><th>Nome do Item:</th><td id="info_nome_item"></td></tr>
            <tr><th>Descrição:</th><td id="info_descricao"></td></tr>
            <tr><th>Marca:</th><td id="info_marca"></td></tr>
            <tr><th>Categoria:</th><td id="info_categoria"></td></tr>
            <tr><th>Quantidade:</th><td id="info_quantidade"></td></tr>
            <tr><th>Data de Aquisição:</th><td id="info_data_aquisicao"></td></tr>
            <tr><th>Fornecedor:</th><td id="info_fornecedor"></td></tr>
            <tr><th>Condição do Item:</th><td id="info_condicao_aquisicao"></td></tr>
            <tr><th>Estado do Item:</th><td id="info_estado_item"></td></tr>
            <tr><th>Custo Unitário (R$):</th><td id="info_custo_unitario"></td></tr>
            <tr><th>Custo Total (R$):</th><td id="info_custo_total"></td></tr>
            <tr><th>Local:</th><td id="info_local"></td></tr>
            <tr><th>Observações:</th><td id="info_observacoes"></td></tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
