// script.js

// Função para atualizar o gráfico com dados fornecidos
function updateChart(data) {
    const ctx = document.getElementById('chartCanvas').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Chaves Escaneadas'],
            datasets: [{
                label: 'Chaves Escaneadas',
                data: data,
                backgroundColor: ['#007bff'],
                borderColor: ['#007bff'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    fontColor: '#ffffff'
                }
            }
        }
    });
}
