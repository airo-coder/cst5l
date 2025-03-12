<?php
session_start();
include 'admin/includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$room_number = $_GET['room_number'] ?? '';
$date = $_GET['date'] ?? '';

if (!$room_number || !$date) {
    echo json_encode([]);
    exit();
}

// Get room ID
$stmt = $pdo->prepare("SELECT id FROM Rooms WHERE room_number = :room_number");
$stmt->execute(['room_number' => $room_number]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    echo json_encode([]);
    exit();
}

// Get approved bookings
$stmt = $pdo->prepare("SELECT timeslot FROM Bookings WHERE room_id = :room_id AND date = :date AND status = 'approved'");
$stmt->execute(['room_id' => $room['id'], 'date' => $date]);
$bookedSlots = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($bookedSlots);
?>