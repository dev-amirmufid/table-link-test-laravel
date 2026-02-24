// Admin Dashboard Page Module
import '../bootstrap'; // Import bootstrap to get window.axios configured

async function loadDashboard() {
    console.log('[Dashboard] loadDashboard called');
    const loadingMessage = document.getElementById('loadingMessage');
    const statsCards = document.getElementById('statsCards');
    const chartsContainer = document.getElementById('chartsContainer');
    const errorMessage = document.getElementById('errorMessage');

    try {
        console.log('[Dashboard] Calling API...');

        // Use window.axios directly (configured in bootstrap.js)
        const response = await window.axios.get('/api/dashboard/charts');
        console.log('[Dashboard] API Response:', response);

        const data = response.data;
        console.log('[Dashboard] Data:', data);

        if (!data.success) {
            throw new Error(data.message || 'Failed to load dashboard data');
        }

        // Update stats
        document.getElementById('totalUsers').textContent = data.stats.total_users || 0;
        document.getElementById('totalFlights').textContent = data.stats.total_flights || 0;
        document.getElementById('totalAdmins').textContent = data.stats.total_admins || 0;
        document.getElementById('totalRegularUsers').textContent = data.stats.total_regular_users || 0;

        // Get chart data from API response
        const lineConfig = data.charts?.line || {};
        const barConfig = data.charts?.bar || {};
        const pieConfig = data.charts?.pie || {};

        console.log('[Dashboard] Chart configs from API:', { lineConfig, barConfig, pieConfig });

        // Render charts
        renderCharts(lineConfig, barConfig, pieConfig);

        // Show content
        loadingMessage.classList.add('hidden');
        statsCards.classList.remove('hidden');
        chartsContainer.classList.remove('hidden');

    } catch (error) {
        console.error('[Dashboard] Error:', error);
        console.error('[Dashboard] Error response:', error.response);

        loadingMessage.classList.add('hidden');
        errorMessage.classList.remove('hidden');
        errorMessage.textContent = 'Error: ' + error.message + (error.response ? ' - ' + JSON.stringify(error.response.data) : '');
    }
}

function renderCharts(lineConfig, barConfig, pieConfig) {
    // Line Chart - Revenue, Expenses, Profit Margin
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: lineConfig.labels || [],
            datasets: lineConfig.datasets || []
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'USD'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: 'Profit (%)'
                    }
                }
            }
        }
    });

    // Bar Chart - Monthly Revenue vs Expenses
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barConfig.labels || [],
            datasets: barConfig.datasets || []
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'USD'
                    }
                }
            }
        }
    });

    // Pie Chart - Product Distribution
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pieConfig.labels || [],
            datasets: pieConfig.datasets || []
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('[Dashboard] Initializing...');
    loadDashboard();
});
