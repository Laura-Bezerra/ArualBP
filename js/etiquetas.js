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
                <span class="codigo">${et.codigo}</span>
                <small class="item">${et.nome_item || ''}</small>
                <small class="setor">${et.setor_nome || ''}</small>
                <button class="etiqueta-print"
                        data-etiqueta='${et.codigo}'
                        data-item='${et.nome_item || ''}'
                        data-setor='${et.setor_nome || ''}'>
                  Imprimir
                </button>
              </div>
            </div>
          `);
        });
      }
    });
  });

  // Impressão individual
  $(document).on('click', '.etiqueta-print', function () {
    const etiqueta = $(this).data('etiqueta');
    const item = $(this).data('item');
    const setor = $(this).data('setor');

    const conteudo = `
      <div class="print-area" style="
        width: 280px; 
        border: 2px solid #441281;
        border-radius: 14px;
        padding: 16px 12px;
        margin: auto;
        text-align: center;
        font-family: Poppins, sans-serif;
        color: #441281;
        box-shadow: 0 0 10px rgba(68,18,129,0.15);
      ">
        <h3 style="margin:0; font-size:18px; color:#441281;">${etiqueta}</h3>
        <p style="margin:2px 0; font-size:12px;"><b>Item:</b> ${item}</p>
        <p style="margin:2px 0; font-size:12px;"><b>Setor:</b> ${setor}</p>
      </div>
    `;

    const janela = window.open('', '_blank', 'width=400,height=450');
    janela.document.write(`
      <html>
        <head>
          <title>Imprimir Etiqueta</title>
          <style>
            @media print {
              body { margin: 0; padding: 0; text-align: center; }
            }
          </style>
        </head>
        <body>${conteudo}</body>
      </html>
    `);
    janela.document.close();
    janela.print();
  });

  // Impressão de todas
  $('#btn-imprimir-todas').on('click', function () {
    if (etiquetasData.length === 0) return;

    let etiquetasHTML = '';
    etiquetasData.forEach(et => {
      etiquetasHTML += `
        <div style="
          display: inline-block;
          border: 2px solid #441281;
          border-radius: 14px;
          padding: 16px 12px;
          margin: 8px;
          width: 250px;
          text-align: center;
          font-family: Poppins, sans-serif;
          color: #441281;
          box-shadow: 0 0 10px rgba(68,18,129,0.15);
        ">
          <h3 style="margin:0; font-size:18px; color:#441281;">${et.codigo}</h3>
          <p style="margin:4px 0; font-size:13px;">${et.descricao}</p>
          <hr style="border:1px dashed #915ad3; margin:6px 0;">
          <p style="margin:2px 0; font-size:12px;"><b>Item:</b> ${et.nome_item || ''}</p>
          <p style="margin:2px 0; font-size:12px;"><b>Setor:</b> ${et.setor_nome || ''}</p>
        </div>
      `;
    });

    const janela = window.open('', '_blank', 'width=800,height=600');
    janela.document.write(`
      <html>
        <head>
          <title>Imprimir Todas as Etiquetas</title>
          <style>
            @media print {
              body { margin: 0; padding: 0; text-align: center; }
            }
          </style>
        </head>
        <body>${etiquetasHTML}</body>
      </html>
    `);
    janela.document.close();
    janela.print();
  });
});
