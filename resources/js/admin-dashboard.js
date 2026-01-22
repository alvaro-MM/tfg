import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {

    // Grafico de usuarios
    const usersCanvas = document.getElementById('usersChart');
    if (usersCanvas) {
        const labels = JSON.parse(usersCanvas.dataset.labels);
        const data = JSON.parse(usersCanvas.dataset.data);

        new Chart(usersCanvas, {
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
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ctx => `${Math.round(ctx.raw)} usuarios` }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, stepSize: 1 } }
                }
            }
        });
    }

    // Grafico de pedidos por hora
    const ordersHourCanvas = document.getElementById('ordersHourChart');
    if (ordersHourCanvas) {
        const labels = JSON.parse(ordersHourCanvas.dataset.labels);
        const data = JSON.parse(ordersHourCanvas.dataset.data);

        new Chart(ordersHourCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pedidos por hora',
                    data: data,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79,70,229,0.2)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, precision: 0 } }
            }
        });
    }

    // Grafico de pedidos ultimos 7 dias
    const ordersCanvas = document.getElementById('ordersChart');
    if (ordersCanvas) {
        const labels = JSON.parse(ordersCanvas.dataset.labels);
        const data = JSON.parse(ordersCanvas.dataset.data);

        new Chart(ordersCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pedidos',
                    data: data,
                    backgroundColor: 'rgba(34,197,94,0.7)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${Math.round(ctx.raw)} pedidos`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});