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

$totalRooms = $pdo->query("SELECT COUNT(*) FROM Rooms")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM Bookings")->fetchColumn();
$pendingBookings = $pdo->query("SELECT COUNT(*) FROM Bookings WHERE status = 'pending'")->fetchColumn();
$approvedBookings = $pdo->query("SELECT COUNT(*) FROM Bookings WHERE status = 'approved'")->fetchColumn();
$rejectedBookings = $pdo->query("SELECT COUNT(*) FROM Bookings WHERE status = 'rejected'")->fetchColumn();
?>

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