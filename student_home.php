<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'admin/includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT name FROM Users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$user_name = $user['name'];

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - UM Library</title>
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

        .wave-divider {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 100px;
            transform: rotate(180deg);
        }

        .wave-divider svg path {
            fill: rgba(255, 255, 255, 0.8);
        }

        .feature-card {
            border: 2px solid var(--um-red);
            transition: all 0.3s;
            overflow: hidden;
            position: relative;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--um-red);
            margin-bottom: 1rem;
        }

        .btn-um {
            background-color: var(--um-yellow);
            color: var(--um-red);
            border: none;
            padding: 12px 30px;
            font-weight: bold;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .btn-um:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(204,0,0,0.2);
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
    <?php include 'student_header.php' ?>
    <section class="hero-section">
        <div class="wave-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M1200 0L0 0 892.25 104.14 1200 0z"></path>
            </svg>
        </div>
        <div class="container text-center">
            <h1 class="display-4 mb-4">Welcome, <?= htmlspecialchars($user_name) ?>!</h1>
            <p class="lead mb-4">University of Mindanao Library Collaboration Rooms</p>
            <a href="student_booking.php" class="btn btn-um btn-lg">
                <i class="fas fa-door-open me-2"></i>Book a Room Now
            </a>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <h2 class="mb-4">About Our Facility</h2>
                    <div class="d-flex gap-3 mb-4">
                        <div class="text-um-red">
                            <i class="fas fa-wifi fa-2x"></i>
                        </div>
                        <div>
                            <h5>Top-Notch Tech</h5>
                            <p class="mb-0">Fast WiFi and modern presentation tools to keep things running smoothly.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="text-um-red">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div>
                            <h5>Collaborative Spaces</h5>
                            <p class="mb-0">Flexible seating for 4-10 person groups</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="images/library.jpg" alt="Library Room" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Key Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <i class="feature-icon fas fa-clock"></i>
                        <h5>Flexible Scheduling</h5>
                        <p>Book in hourly increments from 8 AM to 9 PM</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <i class="feature-icon fas fa-calendar-check"></i>
                        <h5>Real-Time Updates</h5>
                        <p>Instant confirmation and availability status</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <i class="feature-icon fas fa-shield-alt"></i>
                        <h5>Secure Access</h5>
                        <p>UM ID verification for all reservations</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'student_footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>