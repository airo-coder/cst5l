<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$room_id = $_GET['id'] ?? '';

if (empty($room_id)) {
    $_SESSION['error'] = "Invalid room ID.";
    header("Location: ../room_management.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM Rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Room deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting room: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: ../room_management.php");
exit();
?>