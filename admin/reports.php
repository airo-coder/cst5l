<?php
session_start();
include 'includes/header.php';
include 'includes/sidebar.php';

include 'includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$bookingTrends = $pdo->query("
    SELECT DATE_FORMAT(date, '%Y-%m') AS month, COUNT(*) AS bookings
    FROM Bookings
    GROUP BY DATE_FORMAT(date, '%Y-%m')
    ORDER BY month ASC
")->fetchAll(PDO::FETCH_ASSOC);

$bookingLabels = [];
$bookingData = [];
foreach ($bookingTrends as $trend) {
    $bookingLabels[] = date('M Y', strtotime($trend['month']));
    $bookingData[] = $trend['bookings'];
}

$roomUsage = $pdo->query("
    SELECT r.room_number, COUNT(*) AS bookings
    FROM Bookings b
    JOIN Rooms r ON b.room_id = r.id
    GROUP BY r.room_number
    ORDER BY bookings DESC
")->fetchAll(PDO::FETCH_ASSOC);

$roomLabels = [];
$roomData = [];
foreach ($roomUsage as $usage) {
    $roomLabels[] = $usage['room_number'];
    $roomData[] = $usage['bookings'];
}
?>

<div class="main-content container-fluid">
    <h1>Reports & Analytics</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Booking Trends</h5>
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Room Usage</h5>
                    <canvas id="roomUsageChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var bookingCtx = document.getElementById('bookingChart').getContext('2d');
    var bookingChart = new Chart(bookingCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($bookingLabels) ?>,
            datasets: [{
                label: 'Bookings',
                data: <?= json_encode($bookingData) ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var roomUsageCtx = document.getElementById('roomUsageChart').getContext('2d');
    var roomUsageChart = new Chart(roomUsageCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($roomLabels) ?>,
            datasets: [{
                label: 'Usage',
                data: <?= json_encode($roomData) ?>,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>