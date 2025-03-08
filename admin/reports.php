<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

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

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Booking Trends Chart
    var bookingCtx = document.getElementById('bookingChart').getContext('2d');
    var bookingChart = new Chart(bookingCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Bookings',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }]
        }
    });

    // Room Usage Chart
    var roomUsageCtx = document.getElementById('roomUsageChart').getContext('2d');
    var roomUsageChart = new Chart(roomUsageCtx, {
        type: 'bar',
        data: {
            labels: ['Room A', 'Room B', 'Room C', 'Room D'],
            datasets: [{
                label: 'Usage',
                data: [12, 19, 3, 5],
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        }
    });
</script>

<?php include 'includes/footer.php'; ?>