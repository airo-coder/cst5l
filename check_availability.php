<?php
session_start();
include 'admin/includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$room_number = $_GET['room_number'] ?? '';
$date = $_GET['date'] ?? '';

if (!$room_number || !$date) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("SELECT id FROM Rooms WHERE room_number = ?");
$stmt->bind_param("s", $room_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([]);
    $stmt->close();
    $conn->close();
    exit();
}

$room = $result->fetch_assoc();

$stmt = $conn->prepare("SELECT timeslot FROM Bookings WHERE room_id = ? AND date = ? AND status = 'approved'");
$stmt->bind_param("is", $room['id'], $date);
$stmt->execute();
$result = $stmt->get_result();

$bookedSlots = [];
while ($row = $result->fetch_assoc()) {
    $bookedSlots[] = $row['timeslot'];
}

echo json_encode($bookedSlots);

$stmt->close();
$conn->close();
?>