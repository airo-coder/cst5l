<?php
session_start();

// Temporary bypass - remove these lines later
$_SESSION['user_id'] = 1;          // Remove after implementing auth
$_SESSION['user_name'] = "Test User"; // Remove after implementing auth

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection placeholder
// $db = new mysqli('localhost', 'username', 'password', 'database_name');

// Fetch user data placeholder
// $user = $db->query("SELECT * FROM users WHERE id = {$_SESSION['user_id']}")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - UM Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --um-red: #CC0000;
            --um-yellow: #FFD700;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--um-red), var(--um-yellow));
            padding: 100px 0;
            color: white;
        }

        .navbar-custom {
            background-color: var(--um-red) !important;
        }

        .btn-um {
            background-color: var(--um-yellow);
            color: var(--um-red);
            border: none;
            padding: 12px 30px;
            font-weight: bold;
        }

        .feature-card {
            border: 2px solid var(--um-red);
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .booking-section {
            padding: 60px 0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="student_home.php">UM Collaboration Rooms</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="student_home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Welcome back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Student') ?>!</h1>
            <p class="lead mb-4">Reserve your study space in few clicks</p>
            <a href="booking.php" class="btn btn-um btn-lg">New Reservation</a>
        </div>
    </section>

    <section class="booking-section">
        <div class="container">
            <h2 class="text-center mb-5">Upcoming Reservations</h2>
            <div class="row g-4">
                <!-- Placeholder for dynamic content -->
                <?php if(isset($reservations) && count($reservations) > 0): ?>
                    <?php foreach($reservations as $booking): ?>
                        <div class="col-md-4">
                            <div class="card feature-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($booking['room_name']) ?></h5>
                                    <p class="card-text">
                                        <?= date('M d, Y', strtotime($booking['booking_date'])) ?><br>
                                        <?= $booking['time_slot'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No upcoming reservations found</p>
                        <a href="booking.php" class="btn btn-um">Make a Reservation</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Additional sections can be added here -->

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 University of Mindanao Library. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>