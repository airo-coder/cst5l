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

$room_id = $_POST['id'] ?? '';
$room_number = $_POST['room_number'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$equipment = $_POST['equipment'] ?? '';
$floor = $_POST['floor'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($room_id) || empty($room_number) || empty($capacity) || empty($floor)) {
    die("Please fill in all required fields.");
}

$stmt = $conn->prepare("
    UPDATE Rooms 
    SET room_number = ?, capacity = ?, equipment = ?, 
        floor = ?, description = ? 
    WHERE id = ?
");
$stmt->bind_param("sisssi", $room_number, $capacity, $equipment, $floor, $description, $room_id);

if ($stmt->execute()) {
    echo "Room updated successfully!";
} else {
    die("Error updating room: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>