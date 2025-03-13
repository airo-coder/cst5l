<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'admin/includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$room_number = $_POST['room_number'] ?? '';
$date = $_POST['reservation_date'] ?? '';
$time_slot = $_POST['time_slot'] ?? '';
$subject = $_POST['subject'] ?? '';
$purpose = $_POST['purpose'] ?? '';

if (empty($room_number) || empty($date) || empty($time_slot) || empty($subject) || empty($purpose)) {
    $_SESSION['error'] = "Please fill in all fields.";
    header("Location: student_reservation.php?room=$room_number");
    exit();
}

$room_number = htmlspecialchars($room_number);
$date = htmlspecialchars($date);
$time_slot = htmlspecialchars($time_slot);
$subject = htmlspecialchars($subject);
$purpose = htmlspecialchars($purpose);

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $_SESSION['error'] = "Invalid date format.";
    header("Location: student_reservation.php?room=$room_number");
    exit();
}

$query = "SELECT id FROM Rooms WHERE room_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $room_number);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if (!$room) {
    $_SESSION['error'] = "Invalid room number.";
    header("Location: student_reservation.php?room=$room_number");
    exit();
}

$room_id = $room['id'];

$query = "
    SELECT id FROM Bookings 
    WHERE room_id = ? 
    AND date = ? 
    AND timeslot = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $room_id, $date, $time_slot);
$stmt->execute();
$result = $stmt->get_result();
$conflicting_booking = $result->fetch_assoc();

if ($conflicting_booking) {
    $_SESSION['error'] = "The selected time slot is already booked.";
    header("Location: student_reservation.php?room=$room_number");
    exit();
}

$query = "
    INSERT INTO Bookings (user_id, room_id, date, timeslot, subject, purpose, status)
    VALUES (?, ?, ?, ?, ?, ?, 'pending')
";
$stmt = $conn->prepare($query);
$stmt->bind_param("iissss", $_SESSION['user_id'], $room_id, $date, $time_slot, $subject, $purpose);
$stmt->execute();

$reservation_id = $stmt->insert_id;

$query = "
    SELECT b.*, r.room_number 
    FROM Bookings b
    JOIN Rooms r ON b.room_id = r.id
    WHERE b.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$reservation_details = $result->fetch_assoc();

if (!$reservation_details) {
    $_SESSION['error'] = "Failed to fetch reservation details.";
    header("Location: student_reservation.php?room=$room_number");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation - UM Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --um-red: #CC0000;
            --um-yellow: #FFD700;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--um-red), var(--um-yellow));
            padding: 100px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .confirmation-card {
            border: 3px solid var(--um-red);
            border-radius: 15px;
            max-width: 800px;
            margin: 2rem auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            color: var(--um-red);
            font-size: 4rem;
            animation: bounce 1s;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .detail-item {
            background: rgba(204, 0, 0, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .btn-um {
            background-color: var(--um-yellow);
            color: var(--um-red);
            border: none;
            padding: 12px 40px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .footer {
            background: var(--um-red);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 5rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--um-red);">
        <div class="container">
            <a class="navbar-brand" href="student_home.php" style="display: flex; align-items: center;">
                <img src="images/um-logo.png" alt="UM Logo" height="40">
                <span class="ms-2">Collaboration Room Reservation</span>
            </a>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Reservation Confirmed!</h1>
            <p class="lead">Your collaboration space is ready</p>
        </div>
    </section>

    <main class="container my-5">
        <div class="confirmation-card card">
            <div class="card-body text-center">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle"></i>
                </div>

                <div class="text-start">
                    <div class="detail-item">
                        <h5>Room Number:</h5>
                        <p class="lead"><?= htmlspecialchars($reservation_details['room_number']) ?></p>
                    </div>

                    <div class="detail-item">
                        <h5>Date:</h5>
                        <p class="lead"><?= htmlspecialchars($reservation_details['date']) ?></p>
                    </div>

                    <div class="detail-item">
                        <h5>Time Slot:</h5>
                        <p class="lead"><?= htmlspecialchars($reservation_details['timeslot']) ?></p>
                    </div>

                    <div class="detail-item">
                        <h5>Subject:</h5>
                        <p class="lead"><?= htmlspecialchars($reservation_details['subject']) ?></p>
                    </div>

                    <div class="detail-item">
                        <h5>Purpose:</h5>
                        <p class="lead"><?= htmlspecialchars($reservation_details['purpose']) ?></p>
                    </div>
                </div>

                <a href="student_home.php" class="btn btn-um btn-lg mt-4">
                    <i class="fas fa-home me-2"></i>Return to Home
                </a>
            </div>
        </div>
    </main>

   <?php include 'student_footer.php'?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>