<?php
session_start();
// Temporary development bypass - remove in production
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = "Test User";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve form data (will need proper validation later)
$reservation_details = [
    'room_number' => $_POST['room_number'] ?? 'N/A',
    'date' => $_POST['reservation_date'] ?? 'N/A',
    'time_slot' => $_POST['time_slot'] ?? 'N/A',
    'subject' => $_POST['subject'] ?? 'N/A',
    'purpose' => $_POST['purpose'] ?? 'N/A'
];
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

        .confirmation-card {
            border: 3px solid var(--um-red);
            border-radius: 15px;
            max-width: 800px;
            margin: 2rem auto;
        }

        .detail-item {
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 0;
        }

        .success-icon {
            color: var(--um-red);
            font-size: 4rem;
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

    <main class="container mt-5">
        <div class="confirmation-card card">
            <div class="card-body text-center">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="mb-4">Reservation Confirmed!</h2>
                
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
                        <p class="lead"><?= htmlspecialchars($reservation_details['time_slot']) ?></p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>