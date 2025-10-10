document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.editBtn').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.dataset.id;
      const nome = this.dataset.nome;
      const usuario = this.dataset.usuario;
      const email = this.dataset.email;
      const nivel = this.dataset.nivel;

      document.getElementById('edit_id').value = id;
      document.getElementById('edit_nome').value = nome;
      document.getElementById('edit_usuario').value = usuario;
      document.getElementById('edit_email').value = email;
      document.getElementById('edit_nivel').value = nivel;
    });
  });
});
