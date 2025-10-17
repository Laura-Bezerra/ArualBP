document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".btn-edit");

  editButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      const nome = btn.dataset.nome;
      const sigla = btn.dataset.sigla;
      const descricao = btn.dataset.descricao;

      document.getElementById("edit_id").value = id;
      document.getElementById("edit_nome").value = nome;
      document.getElementById("edit_sigla").value = sigla;
      document.getElementById("edit_descricao").value = descricao;
    });
  });
});
