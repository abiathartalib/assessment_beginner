<?php
include "db.php";

// Stats
$clients_count_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM clients");
$clients_count = mysqli_fetch_assoc($clients_count_res)['count'];

// Create services table if not exists (handling the logic from services_list.php here too just in case)
$create = "CREATE TABLE IF NOT EXISTS services (
  service_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) DEFAULT 0.00,
  description VARCHAR(255)
)";
mysqli_query($conn, $create);

$services_count_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM services");
$services_count = mysqli_fetch_assoc($services_count_res)['count'];

// Data for charts
// Top 5 services by price
$top_services_res = mysqli_query($conn, "SELECT service_name, price FROM services ORDER BY price DESC LIMIT 5");
$top_services_labels = [];
$top_services_data = [];
while($row = mysqli_fetch_assoc($top_services_res)) {
    $top_services_labels[] = $row['service_name'];
    $top_services_data[] = $row['price'];
}

$path_to_root = "./";
$page = "dashboard";
$page_title = "Dashboard";

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="row g-4 mb-4 fade-in">
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div>
                <h3 class="fs-2 fw-bold mb-0"><?php echo $clients_count; ?></h3>
                <p class="text-muted mb-0">Total Clients</p>
            </div>
            <div class="stats-icon primary">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div>
                <h3 class="fs-2 fw-bold mb-0"><?php echo $services_count; ?></h3>
                <p class="text-muted mb-0">Total Services</p>
            </div>
            <div class="stats-icon warning">
                <i class="bi bi-briefcase-fill"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div>
                <h3 class="fs-2 fw-bold mb-0">$12,450</h3>
                <p class="text-muted mb-0">Total Revenue</p>
            </div>
            <div class="stats-icon success">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div>
                <h3 class="fs-2 fw-bold mb-0">15</h3>
                <p class="text-muted mb-0">Pending Tasks</p>
            </div>
            <div class="stats-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-list-task"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 fade-in" style="animation-delay: 0.1s;">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header border-0 bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Analytics Overview</h5>
                <select class="form-select form-select-sm w-auto">
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>This Year</option>
                </select>
            </div>
            <div class="card-body">
                <canvas id="overviewChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header border-0 bg-transparent">
                <h5 class="mb-0 fw-bold">Top Services</h5>
            </div>
            <div class="card-body">
                <canvas id="servicesChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Overview Chart
    var ctx = document.getElementById('overviewChart').getContext('2d');
    var overviewChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Clients',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Services',
                data: [5, 10, 8, 15, 20, 15],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Services Chart
    var ctx2 = document.getElementById('servicesChart').getContext('2d');
    var servicesChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($top_services_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($top_services_data); ?>,
                backgroundColor: [
                    '#4f46e5',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#64748b'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });
</script>

<?php include "includes/footer.php"; ?>
