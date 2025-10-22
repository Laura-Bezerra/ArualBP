$(document).ready(function() {
    $('#tipo_relatorio').on('change', function() {
        const tipo = $(this).val();
        let html = '';

        // -----------------------------
        // BPs por Setor ou Usuários por Setor → Filtro de setor
        // -----------------------------
        if (tipo === 'bps_setor' || tipo === 'usuarios_setor') {
            html = `
                <label for="setor">Selecione o Setor:</label>
                <select name="setor_id" id="setor" class="form-select">
                    <option value="">Todos os Setores</option>
                </select>
            `;

            // Carrega setores via AJAX
            $.ajax({
                url: '../actions/pegar_setores.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.forEach(function(setor) {
                        $('#setor').append(`<option value="${setor.id}">${setor.nome}</option>`);
                    });
                },
                error: function() {
                    console.error('Erro ao carregar setores.');
                }
            });
        }

        // -----------------------------
        // BPs por Período → Filtro de datas
        // -----------------------------
        else if (tipo === 'bps_data') {
            html = `
                <label>Data Início:</label>
                <input type="date" name="data_inicio" class="form-control mb-2">
                <label>Data Fim:</label>
                <input type="date" name="data_fim" class="form-control">
            `;
        }

        // -----------------------------
        // Relatório de Todos os Usuários (apenas Admin)
        // -----------------------------
        else if (tipo === 'usuarios') {
            html = `
                <label for="status_usuarios">Filtrar por Status:</label>
                <select name="status_usuarios" id="status_usuarios" class="form-select">
                    <option value="todos">Todos</option>
                    <option value="ativos">Apenas Ativos</option>
                    <option value="inativos">Apenas Desativados</option>
                </select>
                <p class="text-muted mt-2">* Este relatório está disponível apenas para administradores.</p>
            `;
        }

        else if (tipo === 'setores') {  
        }

        else if (tipo === 'categorias') {
        }

        else if (tipo === 'inventario_setor') {
            html = `
                <label for="setor">Selecione o Setor:</label>
                <select name="setor_id" id="setor" class="form-select mb-3">
                    <option value="">Todos os Setores</option>
                </select>

                <label>Campos a incluir no relatório:</label>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="codigo_bp" id="codigo_bp">
                    <label class="form-check-label" for="codigo_bp">Código BP</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="quantidade" id="quantidade">
                    <label class="form-check-label" for="quantidade">Quantidade</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="descricao" id="descricao">
                    <label class="form-check-label" for="descricao">Descrição</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="marca" id="marca">
                    <label class="form-check-label" for="marca">Marca</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="data_aquisicao" id="data_aquisicao">
                    <label class="form-check-label" for="data_aquisicao">Data de Aquisição</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="fornecedor" id="fornecedor">
                    <label class="form-check-label" for="fornecedor">Fornecedor</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="condicao_aquisicao" id="condicao_aquisicao">
                    <label class="form-check-label" for="condicao_aquisicao">Condição de Aquisição</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="estado_item" id="estado_item">
                    <label class="form-check-label" for="estado_item">Estado do Item</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="custo_total" id="custo_total">
                    <label class="form-check-label" for="custo_total">Custo Total</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="custo_unitario" id="custo_unitario">
                    <label class="form-check-label" for="custo_unitario">Custo Unitário</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="observacoes" id="observacoes">
                    <label class="form-check-label" for="observacoes">Observações</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="local" id="local">
                    <label class="form-check-label" for="local">Local</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="campos[]" value="categoria" id="categoria">
                    <label class="form-check-label" for="categoria">Categoria</label></div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="select_all_fields">
                    <label class="form-check-label fw-bold" for="select_all_fields">Selecionar todos os campos</label>
                </div>
            `;

            // Carrega setores via AJAX
            $.ajax({
                url: '../actions/pegar_setores.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.forEach(function(setor) {
                        $('#setor').append(`<option value="${setor.id}">${setor.nome}</option>`);
                    });
                }
            });

            // Checkbox "Selecionar todos"
            $(document).on('change', '#select_all_fields', function() {
                $('input[name="campos[]"]').prop('checked', this.checked);
            });
        }

        else if (tipo === 'solicitacoes') {
            html = `
                <label for="setor">Selecione o Setor:</label>
                <select name="setor_id" id="setor" class="form-select mb-3">
                    <option value="">Todos os Setores</option>
                </select>

                <label for="usuario">Selecione o Usuário:</label>
                <select name="usuario_id" id="usuario" class="form-select mb-3">
                    <option value="">Todos os Usuários</option>
                </select>

                <label>Data de Abertura:</label>
                <div class="d-flex gap-2 mb-3">
                    <input type="date" name="data_inicio" id="data_inicio" class="form-control">
                    <input type="date" name="data_fim" id="data_fim" class="form-control">
                </div>

                <label for="status">Status da Solicitação:</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="Aprovado">Aprovado</option>
                    <option value="Pendente">Pendente</option>
                    <option value="Recusado">Recusado</option>
                </select>
            `;

            // 🔹 Carrega setores disponíveis conforme permissões
            $.ajax({
                url: '../actions/pegar_setores.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.forEach(function(setor) {
                        $('#setor').append(`<option value="${setor.id}">${setor.nome}</option>`);
                    });
                }
            });

            // 🔹 Função para carregar usuários (setor específico ou todos)
            function carregarUsuarios(setorId = '') {
                $('#usuario').html('<option value="">Todos os Usuários</option>');
                $.ajax({
                    url: '../actions/pegar_usuarios_setor.php',
                    method: 'GET',
                    data: { setor_id: setorId },
                    dataType: 'json',
                    success: function(data) {
                        data.forEach(function(user) {
                            $('#usuario').append(`<option value="${user.id}">${user.nome}</option>`);
                        });
                    }
                });
            }

            // Carregar todos os usuários no início
            carregarUsuarios();

            // Quando o setor mudar, recarregar usuários correspondentes
            $(document).on('change', '#setor', function() {
                const setorId = $(this).val();
                carregarUsuarios(setorId);
            });
        }







        // -----------------------------
        // Nenhum filtro adicional
        // -----------------------------
        else {
            html = '';
        }

        // Atualiza o conteúdo e exibe a área
        $('#parametros-relatorio').hide().html(html);
        if (html) {
            $('#parametros-relatorio').slideDown();
        } else {
            $('#parametros-relatorio').slideUp();
        }
    });
});



function createExplosion(element) {
  const colors = ['#441281', '#915ad3', '#f5ad00', '#b388ff'];
  const parent = element.closest('.form-check');
  if (!parent) return;

  const offsetX = -50;

  for (let i = 0; i < 10; i++) {
    const particle = document.createElement('div');
    particle.classList.add('explosion-particle');
    particle.style.background = colors[Math.floor(Math.random() * colors.length)];

    // Gera direção e distância aleatórias
    const angle = Math.random() * 2 * Math.PI;
    const distance = 15 + Math.random() * 20;

    // Aplica o deslocamento horizontal
    particle.style.setProperty('--x', `${Math.cos(angle) * distance + offsetX}px`);
    particle.style.setProperty('--y', `${Math.sin(angle) * distance}px`);

    parent.appendChild(particle);
    setTimeout(() => particle.remove(), 600);
  }
}


// Evento de clique nas checkboxes
document.addEventListener('change', (e) => {
  if (e.target.matches('.form-check-input') && e.target.checked) {
    createExplosion(e.target);
  }
});


