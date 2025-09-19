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
