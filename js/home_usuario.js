// Captura o ID do BP e preenche o campo oculto no modal
var modalSolicitacao = document.getElementById('modalSolicitacao');
modalSolicitacao.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var bpId = button.getAttribute('data-bp-id');
    modalSolicitacao.querySelector('#bp_id').value = bpId;
});
