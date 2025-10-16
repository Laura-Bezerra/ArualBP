// ====== Modal de Edição de Setor ======
const editSetorBtns = document.querySelectorAll('.editSetorBtn');
const editSetorModal = document.getElementById('editSetorModal');

editSetorBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');
    const nome = btn.getAttribute('data-nome');
    const usuario = btn.getAttribute('data-usuario');

    editSetorModal.querySelector('#edit_id').value = id;
    editSetorModal.querySelector('#edit_nome').value = nome;
    editSetorModal.querySelector('#edit_usuario').value = usuario;
  });
});

// ====== Modal de Vincular Usuário ======
const linkUserBtns = document.querySelectorAll('.linkUserBtn');
const vincularModal = document.getElementById('vincularUsuarioModal');

linkUserBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const setorId = btn.getAttribute('data-id');
    vincularModal.querySelector('#vincular_setor_id').value = setorId;
  });
});

// ====== Modal de Vincular Gerente ======
const linkGerenteBtns = document.querySelectorAll('.linkGerenteBtn');
const vincularGerenteModal = document.getElementById('vincularGerenteModal');

linkGerenteBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const setorId = btn.getAttribute('data-id');
    vincularGerenteModal.querySelector('#vincular_gerente_setor_id').value = setorId;
  });
});
