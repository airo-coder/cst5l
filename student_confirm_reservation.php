<?php
session_start();
// Temporary development bypass - remove in production
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = "Test User";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

    <footer class="footer">
    <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i>(082) 123 4567</li>
                        <li><i class="fas fa-envelope me-2"></i>library@umindanao.edu.ph</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Library Hours</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Room Policies</a></li>
                    </ul>
                </div>
                <div class="col-md-4 text-end">
                    <h5>Follow Us</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-center mb-0 small">&copy; 2025 University of Mindanao Library. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>