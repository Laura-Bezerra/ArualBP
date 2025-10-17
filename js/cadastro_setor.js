// ========== MODAL DE EDIÇÃO DE SETOR ==========
document.addEventListener('DOMContentLoaded', function() {
  const editSetorBtns = document.querySelectorAll('.editSetorBtn');
  const editSetorModal = document.getElementById('editSetorModal');

  editSetorBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-id');
      const nome = btn.getAttribute('data-nome');
      const codigo = btn.getAttribute('data-codigo');
      const unidade = btn.getAttribute('data-unidade');

      // Preenche os campos do modal
      editSetorModal.querySelector('#edit_id').value = id;
      editSetorModal.querySelector('#edit_nome').value = nome;
      editSetorModal.querySelector('#edit_codigo').value = codigo || '';
      editSetorModal.querySelector('#edit_unidade').value = unidade || '';
    });
  });

// Vincular gerente
document.querySelectorAll('.linkGerenteBtn').forEach(button => {
  button.addEventListener('click', () => {
    const setorId = button.getAttribute('data-id');
    document.getElementById('vincular_gerente_setor_id').value = setorId;
  });
});

// Vincular usuário
document.querySelectorAll('.linkUserBtn').forEach(button => {
  button.addEventListener('click', () => {
    const setorId = button.getAttribute('data-id');
    document.getElementById('vincular_usuario_setor_id').value = setorId;
  });
});


  // ========== CONFIRMAÇÃO DE EXCLUSÃO ==========
  const deleteBtns = document.querySelectorAll('.deleteBtn');
  deleteBtns.forEach(btn => {
    btn.addEventListener('click', e => {
      if (!confirm('Tem certeza que deseja excluir este setor?')) {
        e.preventDefault();
      }
    });
  });
});
