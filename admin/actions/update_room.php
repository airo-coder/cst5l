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

$room_id = $_POST['id'] ?? '';
$room_number = $_POST['room_number'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$equipment = $_POST['equipment'] ?? '';
$floor = $_POST['floor'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($room_id) || empty($room_number) || empty($capacity) || empty($floor)) {
    die("Please fill in all required fields.");
}

try {
    $stmt = $pdo->prepare("
        UPDATE Rooms 
        SET room_number = :room_number, capacity = :capacity, equipment = :equipment, 
            floor = :floor, description = :description 
        WHERE id = :id
    ");
    $stmt->execute([
        'room_number' => $room_number,
        'capacity' => $capacity,
        'equipment' => $equipment,
        'floor' => $floor,
        'description' => $description,
        'id' => $room_id
    ]);

    echo "Room updated successfully!";
} catch (PDOException $e) {
    die("Error updating room: " . $e->getMessage());
}
?>