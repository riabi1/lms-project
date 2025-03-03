@extends('admin.admin_dashboard')
@section('admin')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');
    body, .card, .card-title, .card-text, small {
        font-family: 'Open Sans', sans-serif;
        font-weight: 400;
    }
    h5.card-title {
        font-weight: 600;
        color: #ffffff; /* Blanc pour contraste sur dégradé */
    }
    /* Conteneur global pour les stats */
    .stats-container {
        background: linear-gradient(135deg, #5a7db5, #3e5f9c);
        padding: 30px 20px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        margin-bottom: 40px;
    }
    .stats-header {
        color: white;
        margin-bottom: 20px;
    }
    .stats-header h2 {
        font-size: 2rem;
        margin: 0;
        display: flex;
        align-items: center;
    }
    .stats-header i {
        margin-right: 15px;
        font-size: 2.5rem;
    }
    /* Cartes améliorées */
    .card {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 249, 252, 0.9));
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .card-icon {
        width: 70px;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid rgba(224, 224, 224, 0.5);
        transition: transform 0.3s ease;
    }
    .card:hover .card-icon {
        transform: scale(1.1);
    }
    .card-content {
        padding: 20px;
        flex-grow: 1;
        color: #2c3e50; /* Gris foncé pour texte sur fond clair */
    }
    .card-content h5 {
        margin: 0 0 8px 0;
        font-size: 1.2rem;
        color: inherit; /* Hérite de la couleur définie dans .card */
    }
    .card-content .display-6 {
        margin: 0;
        font-size: 2.5rem;
        line-height: 1;
    }
    .card-content small {
        display: block;
        color: #555555;
        font-size: 0.9rem;
        margin-top: 5px;
    }
    /* Graphiques */
    .chart-card {
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        background: #ffffff;
        padding: 20px;
    }
    #popularCoursesChart {
        max-height: 500px;
    }
</style>

<div class="page-content">
    <!-- Statistiques rapides dans un conteneur stylé -->
    <div class="stats-container">
        <div class="stats-header">
            <h2><i class="bx bx-tachometer"></i> Tableau de bord Admin</h2>
        </div>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card d-flex flex-row h-100" style="color: #5a7db5;">
                    <div class="card-icon" style="border-left: 5px solid #5a7db5;">
                        <i class="bx bx-user-circle" style="font-size: 2.5rem; color: #5a7db5;"></i>
                    </div>
                    <div class="card-content">
                        <h5 class="card-title">Utilisateurs</h5>
                        <p class="card-text display-6">{{ $totalUsers }}</p>
                        <small>Instructeurs: {{ $totalInstructors }}</small>
                        <small>Étudiants: {{ $totalStudents }}</small>
                        <small>Nouveaux: {{ $newUsers }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card d-flex flex-row h-100" style="color: #f18786;">
                    <div class="card-icon" style="border-left: 5px solid #f18786;">
                        <i class="bx bx-book" style="font-size: 2.5rem; color: #f18786;"></i>
                    </div>
                    <div class="card-content">
                        <h5 class="card-title">Cours</h5>
                        <p class="card-text display-6">{{ $totalCourses }}</p>
                        <small>Actifs: {{ $activeCourses }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card d-flex flex-row h-100" style="color: #ebba4d;">
                    <div class="card-icon" style="border-left: 5px solid #ebba4d;">
                        <i class="bx bx-cart" style="font-size: 2.5rem; color: #ebba4d;"></i>
                    </div>
                    <div class="card-content">
                        <h5 class="card-title">Commandes</h5>
                        <p class="card-text display-6">{{ $totalOrders }}</p>
                        <small>En attente: {{ $pendingOrders }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card d-flex flex-row h-100" style="color: #5a7db5;">
                    <div class="card-icon" style="border-left: 5px solid #5a7db5;">
                        <i class="bx bx-gift" style="font-size: 2.5rem; color: #5a7db5;"></i>
                    </div>
                    <div class="card-content">
                        <h5 class="card-title">Coupons</h5>
                        <p class="card-text display-6">{{ $totalCoupons }}</p>
                        <small>Actifs: {{ $activeCoupons }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="chart-card">
                <h5 class="card-title">Revenus mensuels (dernière année)</h5>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="chart-card">
                <h5 class="card-title">Cours les plus populaires</h5>
                <canvas id="popularCoursesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Inclure Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Nouvelle palette de couleurs
const primaryColor = '#5a7db5';
const secondaryColor = '#f18786';
const accentColor = '#ebba4d';
const darkGray = '#2c3e50';
const midGray = '#555555';
const lightGray = '#e0e0e0';

// Débogage des données
console.log("Monthly Revenue Data:", @json($monthlyRevenue));
console.log("Popular Courses Data:", @json($popularCourses));

// Revenus mensuels (Chart.js Bar Chart)
const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
const revenueChart = new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenus ($)',
            data: @json(array_values($monthlyRevenue)),
            backgroundColor: primaryColor,
            borderColor: darkGray,
            borderWidth: 1,
            hoverBackgroundColor: '#3e5f9c',
            hoverBorderColor: midGray
        }]
    },
    options: {
        responsive: true,
        animation: {
            duration: 1000,
            easing: 'easeInOutQuad'
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: { color: midGray, font: { family: 'Open Sans' } }
            },
            x: {
                grid: { display: false },
                ticks: { color: midGray, font: { family: 'Open Sans' } }
            }
        },
        plugins: {
            legend: { labels: { color: midGray, font: { family: 'Open Sans' } } },
            tooltip: {
                backgroundColor: primaryColor,
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: lightGray,
                borderWidth: 1
            }
        }
    }
});

// Cours les plus populaires (Chart.js Doughnut Chart)
const popularCoursesChart = new Chart(document.getElementById('popularCoursesChart'), {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($popularCourses)),
        datasets: [{
            label: 'Nombre de commandes',
            data: @json(array_values($popularCourses)),
            backgroundColor: [
                primaryColor, secondaryColor, accentColor,
                '#7a9ad1', '#f7a8a7', '#ffd374'
            ],
            borderColor: '#fff',
            borderWidth: 3,
            hoverOffset: 10,
            weight: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1000,
            easing: 'easeInOutQuad'
        },
        cutout: '50%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: midGray,
                    padding: 15,
                    boxWidth: 12,
                    font: { size: 12, family: 'Open Sans' }
                }
            },
            tooltip: {
                backgroundColor: secondaryColor,
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: lightGray,
                borderWidth: 1
            }
        }
    }
});
</script>
@endsection