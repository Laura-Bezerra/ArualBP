$(document).ready(function(){
    $('#tipo_relatorio').change(function(){
        let tipo = $(this).val();
        let html = '';

        if(tipo === 'bps_setor') {
            html = `<label for="setor">Selecione o Setor:</label>
                    <select name="setor" id="setor" class="form-select">
                        <option value="">Todos os Setores</option>
                    </select>`;

            $.ajax({
                url: '../actions/pegar_setores.php',
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    data.forEach(function(setor){
                        $('#setor').append(`<option value="${setor.id}">${setor.nome}</option>`);
                    });
                }
            });
        } else if(tipo === 'bps_data') {
            html = `<label>Data Início:</label><input type="date" name="data_inicio" class="form-control">
                    <label>Data Fim:</label><input type="date" name="data_fim" class="form-control">`;
        } else if(tipo === 'usuarios') {
            html = '<p>Sem parâmetros adicionais.</p>';
        }

        $('#parametros-relatorio').html(html);
    });
});
