$(document).ready(function () {
  let etiquetasData = [];

  // Carregar etiquetas no modal
  $('#etiquetaModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const bpId = button.data('bp-id');
    const lista = $('#lista-etiquetas');
    lista.html('<p class="text-center text-muted w-100">Carregando...</p>');

    $.getJSON(`../actions/get_etiquetas.php?bp_id=${bpId}`, function (data) {
      etiquetasData = data;
      lista.empty();

      if (!data || data.length === 0) {
        lista.append("<p class='text-center w-100'>Nenhuma etiqueta encontrada.</p>");
      } else {
        data.forEach(et => {
          lista.append(`
            <div class="col-md-4">
              <div class="etiqueta-card">
                <span>${et.codigo}</span>
                <small>${et.descricao}</small>
                <button class="etiqueta-print" data-etiqueta="${et.codigo}" data-descricao="${et.descricao}">🖨️ Imprimir</button>
              </div>
            </div>
          `);
        });
      }
    });
  });

  // Imprimir individual
  $(document).on('click', '.etiqueta-print', function () {
    const etiqueta = $(this).data('etiqueta');
    const descricao = $(this).data('descricao');

    const conteudo = `
      <div class="print-area">
        <div style="border:2px solid #441281; border-radius:10px; padding:20px; width:220px; text-align:center; font-weight:bold; color:#441281; margin:auto;">
          ${etiqueta}<br>
          <small>${descricao}</small>
        </div>
      </div>
    `;
    const janela = window.open('', '_blank', 'width=400,height=400');
    janela.document.write('<html><head><title>Imprimir Etiqueta</title></head><body>');
    janela.document.write(conteudo);
    janela.document.write('</body></html>');
    janela.document.close();
    janela.print();
  });

  // Imprimir todas
  $('#btn-imprimir-todas').on('click', function () {
    if (etiquetasData.length === 0) return;

    let etiquetasHTML = '';
    etiquetasData.forEach(et => {
      etiquetasHTML += `
        <div style="border:2px solid #441281; border-radius:10px; padding:20px; width:220px; text-align:center; font-weight:bold; color:#441281; margin:10px; display:inline-block;">
          ${et.codigo}<br>
          <small>${et.descricao}</small>
        </div>
      `;
    });

    const janela = window.open('', '_blank', 'width=800,height=600');
    janela.document.write('<html><head><title>Imprimir Todas as Etiquetas</title></head><body style="text-align:center;">');
    janela.document.write(etiquetasHTML);
    janela.document.write('</body></html>');
    janela.document.close();
    janela.print();
  });
});
