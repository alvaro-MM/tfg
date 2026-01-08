import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('usersChart');
    if (!canvas) return;

    const labels = JSON.parse(canvas.dataset.labels);
    const data = JSON.parse(canvas.dataset.data);

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(74,222,128,0.2)',
                borderColor: '#229fc5ff',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#229fc5ff'
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${Math.round(context.raw)} usuarios`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1,
                        callback: function(value) {
                            return Math.round(value);
                        }
                    }
                }
            }
        }
    });
});
