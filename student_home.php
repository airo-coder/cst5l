<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'admins/includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$stmt = $pdo->prepare("SELECT name FROM Users WHERE id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$user_name = $user['name'];
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
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--um-red);">
        <div class="container">
            <a class="navbar-brand" href="student_home.php" style="display: flex; align-items: center;">
                <img src="images/um-logo.png" alt="UM Logo" height="40">
                <span class="ms-2">Collaboration Room Reservation</span>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="student_home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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