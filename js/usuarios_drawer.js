document.addEventListener('DOMContentLoaded', () => {
  const drawer = document.getElementById('drawerUsuario');
  const btnNovo = document.getElementById('btnNovoUsuario');
  const btnFechar = document.getElementById('btnFecharDrawer');

  btnNovo.addEventListener('click', () => drawer.classList.add('open'));
  btnFechar.addEventListener('click', () => drawer.classList.remove('open'));

  // Fechar clicando fora
  drawer.addEventListener('click', (e) => {
    if (e.target === drawer) drawer.classList.remove('open');
  });
});
