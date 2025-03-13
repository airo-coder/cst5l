<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "<br>";
    echo "Role: " . ($_SESSION['role'] ?? 'Not set') . "<br>";
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$booking_id = $_GET['id'] ?? '';

if (empty($booking_id)) {
    $_SESSION['error'] = "Invalid booking ID.";
    header("Location: ../booking_management.php");
    exit();
}

$stmt = $conn->prepare("UPDATE bookings SET status = 'rejected' WHERE id = ?");
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Booking rejected successfully!";
} else {
    $_SESSION['error'] = "Error rejecting booking: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: ../booking_management.php");
exit();
?>