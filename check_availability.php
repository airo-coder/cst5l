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
    echo "Please provide a room number and date.";
    exit();
}

$stmt = $conn->prepare("SELECT id FROM Rooms WHERE room_number = ?");
$stmt->bind_param("s", $room_number);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if (!$room) {
    echo "Room not found.";
    exit();
}

$stmt = $conn->prepare("SELECT timeslot FROM Bookings WHERE room_id = ? AND date = ? AND status = 'approved'");
$stmt->bind_param("is", $room['id'], $date);
$stmt->execute();
$result = $stmt->get_result();
$bookedSlots = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Timeslots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Booked Timeslots for Room <?= htmlspecialchars($room_number) ?> on <?= htmlspecialchars($date) ?></h1>
        
        <?php if (empty($bookedSlots)): ?>
            <div class="alert alert-info">
                No bookings found for this room on the selected date.
            </div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($bookedSlots as $slot): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($slot['timeslot']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
