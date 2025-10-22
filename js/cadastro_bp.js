document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".editBtn");

  editButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      // IDs do modal
      document.getElementById("edit-id").value = this.dataset.id;
      document.getElementById("edit-nome_item").value = this.dataset.nome_item || "";
      document.getElementById("edit-descricao").value = this.dataset.descricao || "";
      document.getElementById("edit-marca").value = this.dataset.marca || "";
      document.getElementById("edit-categoria").value = this.dataset.categoria_id || "";
      document.getElementById("edit-quantidade").value = this.dataset.quantidade || "";
      document.getElementById("edit-custo_unitario").value = this.dataset.custo_unitario || "";
      document.getElementById("edit-custo_total").value = this.dataset.custo_total || "";
      document.getElementById("edit-data_aquisicao").value = this.dataset.data_aquisicao || "";
      document.getElementById("edit-fornecedor").value = this.dataset.fornecedor || "";
      document.getElementById("edit-local").value = this.dataset.local || "";
      document.getElementById("edit-condicao_aquisicao").value = this.dataset.condicao_aquisicao || "";
      document.getElementById("edit-estado_item").value = this.dataset.estado_item || "";
      document.getElementById("edit-observacoes").value = this.dataset.observacoes || "";
    });
  });

  // Cálculo automático no modal de edição
  const qtd = document.getElementById("edit-quantidade");
  const custo = document.getElementById("edit-custo_unitario");
  const total = document.getElementById("edit-custo_total");

  if (qtd && custo && total) {
    function calcularTotal() {
      const q = parseFloat(qtd.value) || 0;
      const c = parseFloat(custo.value) || 0;
      total.value = (q * c).toFixed(2);
    }

    qtd.addEventListener("input", calcularTotal);
    custo.addEventListener("input", calcularTotal);
  }
});

// === Preenche o modal de informações ===
document.addEventListener("DOMContentLoaded", () => {
  const infoButtons = document.querySelectorAll(".infoBtn");
  const modal = document.getElementById("infoModal");

  infoButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      modal.querySelector("#info_id").textContent = btn.dataset.id || '-';
      modal.querySelector("#info_codigo_bp").textContent = btn.dataset.codigo_bp || '-';
      modal.querySelector("#info_nome_item").textContent = btn.dataset.nome_item || '-';
      modal.querySelector("#info_descricao").textContent = btn.dataset.descricao || '-';
      modal.querySelector("#info_marca").textContent = btn.dataset.marca || '-';
      modal.querySelector("#info_categoria").textContent = btn.dataset.categoria || '-';
      modal.querySelector("#info_quantidade").textContent = btn.dataset.quantidade || '-';
      modal.querySelector("#info_data_aquisicao").textContent = btn.dataset.data_aquisicao || '-';
      modal.querySelector("#info_fornecedor").textContent = btn.dataset.fornecedor || '-';
      modal.querySelector("#info_condicao_aquisicao").textContent = btn.dataset.condicao_aquisicao || '-';
      modal.querySelector("#info_estado_item").textContent = btn.dataset.estado_item || '-';
      modal.querySelector("#info_custo_unitario").textContent = btn.dataset.custo_unitario || '-';
      modal.querySelector("#info_custo_total").textContent = btn.dataset.custo_total || '-';
      modal.querySelector("#info_local").textContent = btn.dataset.local || '-';
      modal.querySelector("#info_observacoes").textContent = btn.dataset.observacoes || '-';
    });
  });
});
