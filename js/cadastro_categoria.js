document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.getElementById("edit_id").value = btn.dataset.id;
      document.getElementById("edit_nome").value = btn.dataset.nome;
    });
  });
});
