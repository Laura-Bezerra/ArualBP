document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".editBtn");

  editButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      document.getElementById("edit-id").value = this.dataset.id;
      document.getElementById("edit-descricao").value = this.dataset.descricao;
      document.getElementById("edit-marca").value = this.dataset.marca;
      document.getElementById("edit-modelo").value = this.dataset.modelo;
      document.getElementById("edit-quantidade").value = this.dataset.quantidade;
      document.getElementById("edit-data_aquisicao").value = this.dataset.data_aquisicao;
      document.getElementById("edit-valor_total").value = this.dataset.valor_total;
      document.getElementById("edit-local").value = this.dataset.local;
      if (this.dataset.especificacoes) {
        document.getElementById("edit-especificacoes").value = this.dataset.especificacoes;
      }
    });
  });
});
