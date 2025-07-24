    // Pie Chart
if (typeof donutData !== "undefined") {
  const labels = donutData.map(label => label.name);
  const data = donutData.map(label => label.percentage);
  const backgroundColors = donutData.map(label => label.colour);

  new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: backgroundColors
      }]
    },
    options: {
      plugins: {
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.parsed;
              return `${label}: ${value}%`;
            }
          }
        }
      }
    }
  });
}

//BarChart
if (typeof weeklyExpenseData !== 'undefined') {
  new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
      labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
      datasets: [
        {
          label: 'Cette semaine',
          data: weeklyExpenseData.current_week,
          backgroundColor: '#6366f1',
          barThickness: 12,
          borderRadius: 6
        },
        {
          label: 'Semaine dernière',
          data: weeklyExpenseData.last_week,
          backgroundColor: '#93c5fd',
          barThickness: 12,
          borderRadius: 6
        }
      ]
    },
    options: {
      plugins: {
        legend: { display: true, position: 'bottom' }
      },
      responsive: true,
      scales: {
        x: { grid: { display: false } },
        y: { beginAtZero: true, ticks: { stepSize: 20 } }
      }
    }
  });
}