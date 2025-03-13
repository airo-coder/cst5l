<?php
session_start();
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$totalRooms = $conn->query("SELECT COUNT(*) FROM Rooms")->fetch_row()[0];
$totalBookings = $conn->query("SELECT COUNT(*) FROM Bookings")->fetch_row()[0];
$pendingBookings = $conn->query("SELECT COUNT(*) FROM Bookings WHERE status = 'pending'")->fetch_row()[0];
$approvedBookings = $conn->query("SELECT COUNT(*) FROM Bookings WHERE status = 'approved'")->fetch_row()[0];
$rejectedBookings = $conn->query("SELECT COUNT(*) FROM Bookings WHERE status = 'rejected'")->fetch_row()[0];

$conn->close();
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="main-content container-fluid">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Rooms</h5>
                    <p class="card-text"><?= htmlspecialchars($totalRooms) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text"><?= htmlspecialchars($totalBookings) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Bookings</h5>
                    <p class="card-text"><?= htmlspecialchars($pendingBookings) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Rejected Bookings</h5>
                    <p class="card-text"><?= htmlspecialchars($rejectedBookings) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>