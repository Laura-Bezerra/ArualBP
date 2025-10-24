document.addEventListener('DOMContentLoaded', () => {
  fetch('../actions/dados_dashboard_gerente.php')
    .then(res => res.json())
    .then(data => {
      // ===== Gráfico 1: BPs por Setor =====
      const ctx1 = document.getElementById('bpsPorSetor');
      if (ctx1 && data.bpsPorSetor) {
        new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: data.bpsPorSetor.labels,
            datasets: [{
              label: 'Quantidade',
              data: data.bpsPorSetor.data,
              backgroundColor: '#915ad3'
            }]
          },
          options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
          }
        });
      }

      // ===== Gráfico 2: Estado dos Itens =====
        const ctx2 = document.getElementById('estadoItens');
        if (ctx2 && data.estadoItens) {
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
            labels: data.estadoItens.labels,
            datasets: [{
                data: data.estadoItens.data,
                backgroundColor: ['#441281', '#f5ad00', '#915ad3']
            }]
            },
            options: {
            plugins: {
                legend: {
                position: 'bottom',   // 🔹 legenda abaixo do gráfico
                labels: {
                    boxWidth: 14,
                    padding: 15,
                    color: '#441281',
                    font: {
                    size: 13,
                    weight: '500'
                    }
                }
                }
            },
            cutout: '65%', // tamanho do “buraco” do gráfico
            layout: {
                padding: { bottom: 10 } // dá um respiro abaixo do gráfico
            }
            }
        });
        }

    });
});
