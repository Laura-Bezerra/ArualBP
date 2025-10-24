// =============== DASHBOARD ARUALBP ===============

// Busca dados do backend
async function carregarDashboard() {
  try {
    const response = await fetch('../actions/dados_dashboard_admin.php');
    const data = await response.json();

    if (data) {
      renderizarGraficoSetores(data.bpsPorSetor);
      renderizarGraficoEstados(data.estadoItens);
    }
  } catch (error) {
    console.error('Erro ao carregar dados do dashboard:', error);
  }
}

// ===== GRÁFICO 1 - BPs por Setor =====
function renderizarGraficoSetores(dataset) {
  const ctx1 = document.getElementById('bpsPorSetor');
  if (!ctx1) return;

  new Chart(ctx1, {
    type: 'bar',
    data: {
      labels: dataset.labels,
      datasets: [{
        label: 'Quantidade',
        data: dataset.data,
        backgroundColor: dataset.labels.map(() => '#915ad3'),
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true, // mantém proporção
      plugins: { legend: { display: false } },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { color: '#441281' },
          grid: { color: 'rgba(68,18,129,0.1)' }
        },
        x: {
          ticks: { color: '#441281' }
        }
      }
    }
  });
}

// ===== GRÁFICO 2 - Estado dos Itens =====
function renderizarGraficoEstados(dataset) {
  const ctx2 = document.getElementById('estadoItens');
  if (!ctx2) return;

  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: dataset.labels,
      datasets: [{
        data: dataset.data,
        backgroundColor: ['#441281', '#f5ad00', '#915ad3', '#bbb'],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true, // mantém proporção
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#441281',
            font: { size: 14 }
          }
        }
      },
      cutout: '65%',
      animation: {
        animateRotate: true,
        duration: 900
      }
    }
  });
}

// Inicializa
carregarDashboard();
