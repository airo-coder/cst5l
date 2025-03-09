<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$room_id = $_GET['id'] ?? '';

if (empty($room_id)) {
    $_SESSION['error'] = "Invalid room ID.";
    header("Location: ../room_management.php");
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM Rooms WHERE id = :id");
    $stmt->execute(['id' => $room_id]);

    $_SESSION['success'] = "Room deleted successfully!";
} catch (PDOException $e) {
    $_SESSION['error'] = "Error deleting room: " . $e->getMessage();
}

header("Location: ../room_management.php");
exit();
?>