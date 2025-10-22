// ========================================================
// JS: modal_bp.js
// Funções para cálculo automático do custo total
// nos modais de ADICIONAR e EDITAR Bens Patrimoniais
// ========================================================

document.addEventListener("DOMContentLoaded", () => {
  /* ========== MODAL ADICIONAR ========== */
  const qtdAdd = document.getElementById("quantidade");
  const custoAdd = document.getElementById("custo_unitario");
  const totalAdd = document.getElementById("custo_total");

  function calcularTotalAdd() {
    const q = parseFloat(qtdAdd?.value) || 0;
    const c = parseFloat(custoAdd?.value) || 0;
    if (totalAdd) totalAdd.value = (q * c).toFixed(2);
  }

  if (qtdAdd && custoAdd) {
    qtdAdd.addEventListener("input", calcularTotalAdd);
    custoAdd.addEventListener("input", calcularTotalAdd);
  }

  /* ========== MODAL EDITAR ========== */
  const qtdEdit = document.getElementById("edit-quantidade");
  const custoEdit = document.getElementById("edit-custo_unitario");
  const totalEdit = document.getElementById("edit-custo_total");

  function calcularTotalEdit() {
    const q = parseFloat(qtdEdit?.value) || 0;
    const c = parseFloat(custoEdit?.value) || 0;
    if (totalEdit) totalEdit.value = (q * c).toFixed(2);
  }

  if (qtdEdit && custoEdit) {
    qtdEdit.addEventListener("input", calcularTotalEdit);
    custoEdit.addEventListener("input", calcularTotalEdit);
  }

  /* ========== EVENTO PARA RESETAR O FORMULÁRIO ========== */
  const addModal = document.getElementById("addModal");
  if (addModal) {
    addModal.addEventListener("hidden.bs.modal", () => {
      addModal.querySelector("form").reset();
      if (totalAdd) totalAdd.value = "";
    });
  }

  /* ========== EVENTO PARA CALCULAR AO ABRIR EDIÇÃO ========== */
  const editModal = document.getElementById("editModal");
  if (editModal) {
    editModal.addEventListener("shown.bs.modal", () => {
      calcularTotalEdit();
    });
  }
});
