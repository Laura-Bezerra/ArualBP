<?php
// Função para definir uma mensagem flash
function setFlash($tipo, $mensagem, $dadosForm = null) {
    $_SESSION['flash'] = [
        'tipo' => $tipo, // success | error | warning | info
        'mensagem' => $mensagem,
        'dados' => $dadosForm
    ];
}

// Função para exibir e limpar a mensagem flash
function showFlash() {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];

        $tipoClasse = match($flash['tipo']) {
            'success' => 'alert-success',
            'error'   => 'alert-danger',
            'warning' => 'alert-warning',
            default   => 'alert-info'
        };

        echo "<div class='alert $tipoClasse alert-dismissible fade show' role='alert' style='margin-bottom:15px;'>
                {$flash['mensagem']}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Fechar'></button>
              </div>";

        unset($_SESSION['flash']);
    }
}

// Função para recuperar dados do formulário anteriores (se houver)
function old($campo) {
    return htmlspecialchars($_SESSION['flash']['dados'][$campo] ?? '');
}
?>
