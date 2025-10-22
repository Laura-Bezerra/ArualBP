document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('modalSolicitacao');
  const tipoSelect = document.getElementById('tipo_solicitacao');
  const campoSelect = document.getElementById('campo_alterar');
  const valorAtual = document.getElementById('valor_atual');
  const camposAlteracao = document.getElementById('campos-alteracao');
  const novoValorContainer = document.getElementById('campo-novo-valor-container');

  // Ao abrir o modal
  modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const bpId = button.getAttribute('data-bp-id');
    modal.querySelector('#bp_id').value = bpId;

    // Armazena os valores do item
    modal.dataset.valores = JSON.stringify({
      nome_item: button.getAttribute('data-nome_item'),
      descricao: button.getAttribute('data-descricao'),
      marca: button.getAttribute('data-marca'),
      modelo: button.getAttribute('data-modelo'),
      quantidade: button.getAttribute('data-quantidade'),
      local: button.getAttribute('data-local'),
      fornecedor: button.getAttribute('data-fornecedor'),
      custo_unitario: button.getAttribute('data-custo_unitario'),
      custo_total: button.getAttribute('data-custo_total'),
      condicao_aquisicao: button.getAttribute('data-condicao_aquisicao'),
      estado_item: button.getAttribute('data-estado_item'),
      observacoes: button.getAttribute('data-observacoes')
    });

    tipoSelect.value = '';
    campoSelect.value = '';
    camposAlteracao.style.display = 'none';
    valorAtual.value = '';
  });

  // Mostrar/ocultar campos conforme tipo
  tipoSelect.addEventListener('change', function () {
    if (this.value === 'alteracao') {
      camposAlteracao.style.display = 'block';
    } else {
      camposAlteracao.style.display = 'none';
      valorAtual.value = '';
    }
  });

  // Quando escolher o campo a alterar
  campoSelect.addEventListener('change', function () {
    const campo = this.value;
    const valores = JSON.parse(modal.dataset.valores || '{}');
    valorAtual.value = valores[campo] || '';

    novoValorContainer.innerHTML = '';

    if (campo === 'condicao_aquisicao') {
      novoValorContainer.innerHTML = `
        <label for="novo_valor" class="form-label">Nova condição:</label>
        <select class="form-select" id="novo_valor" name="novo_valor" required>
          <option value="">Selecione...</option>
          <option value="Novo Funcionando">Novo Funcionando</option>
          <option value="Usado Funcionando">Usado Funcionando</option>
          <option value="Usado com defeito">Usado com Defeito</option>
        </select>
      `;
    } else if (campo === 'estado_item') {
      novoValorContainer.innerHTML = `
        <label for="novo_valor" class="form-label">Novo estado:</label>
        <select class="form-select" id="novo_valor" name="novo_valor" required>
          <option value="">Selecione...</option>
          <option value="Bom">Bom</option>
          <option value="Em manutenção">Em manutenção</option>
          <option value="Mau Estado">Mau Estado</option>
        </select>
      `;
    } else if (['quantidade', 'custo_unitario', 'custo_total'].includes(campo)) {
      novoValorContainer.innerHTML = `
        <label for="novo_valor" class="form-label">Novo valor:</label>
        <input type="number" step="0.01" class="form-control" id="novo_valor" name="novo_valor" required>
      `;
    } else {
      novoValorContainer.innerHTML = `
        <label for="novo_valor" class="form-label">Novo valor desejado:</label>
        <input type="text" class="form-control" id="novo_valor" name="novo_valor" required>
      `;
    }
  });
});

// ==================== HOME_USUARIO.JS ====================

// Modal: preencher BP ID ao abrir
var modalSolicitacao = document.getElementById('modalSolicitacao');
if (modalSolicitacao) {
    modalSolicitacao.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var bpId = button.getAttribute('data-bp-id');
        modalSolicitacao.querySelector('#bp_id').value = bpId;
    });
}

// Popovers (ícone de exclamação)
document.addEventListener('DOMContentLoaded', function () {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popovers = popoverTriggerList.map(function (el) {
        const pop = new bootstrap.Popover(el);

        // Fechar ao clicar fora
        document.addEventListener('click', function (event) {
            if (!el.contains(event.target)) {
                pop.hide();
            }
        });

        // Fechar automaticamente após 4 segundos
        el.addEventListener('shown.bs.popover', function () {
            setTimeout(() => pop.hide(), 4000);
        });

        return pop;
    });
});

