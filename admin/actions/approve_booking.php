<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {

    echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "<br>";
    echo "Role: " . ($_SESSION['role'] ?? 'Not set') . "<br>";
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$booking_id = $_GET['id'] ?? '';

if (empty($booking_id)) {
    $_SESSION['error'] = "Invalid booking ID.";
    header("Location: ../booking_management.php");
    exit();
}

try {
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'approved' WHERE id = :id");
    $stmt->execute(['id' => $booking_id]);

    $_SESSION['success'] = "Booking approved successfully!";
} catch (PDOException $e) {
    $_SESSION['error'] = "Error approving booking: " . $e->getMessage();
}

header("Location: ../booking_management.php");
exit();
?>