<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'admin/includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$room_number = $_GET['room'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM Rooms WHERE room_number = :room_number");
$stmt->execute(['room_number' => $room_number]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    $_SESSION['error'] = "Room not found.";
    header("Location: student_home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form - UM Library</title>
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

        .reservation-card {
            border: 2px solid var(--um-red);
            border-radius: 15px;
            margin: 2rem auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }

        .time-slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
        }

        .time-slot-card {
            border: 2px solid var(--um-red);
            border-radius: 8px;
            transition: all 0.2s;
            text-align: center;
        }

        .time-slot-card label {
            padding: 0.75rem;
            margin: 0;
            cursor: pointer;
            font-size: 0.9rem;
            display: block;
        }

        .time-slot-card:hover {
            background-color: rgba(204, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .time-slot-card input[type="radio"]:checked+label {
            background-color: var(--um-red);
            color: white;
            font-weight: 500;
        }

        .time-slot-card input[type="radio"]:checked+label::after {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-left: 8px;
            font-size: 0.9em;
        }

        .btn-um {
            background-color: var(--um-yellow);
            color: var(--um-red);
            border: none;
            padding: 12px 40px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-um:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.2);
        }

        .wave-divider svg path {
            fill: rgba(255, 255, 255, 0.8);
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
                        <a class="nav-link" href="student_home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
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
            <h1 class="display-4 mb-4">Reserve Room <?= htmlspecialchars($room['room_number']) ?></h1>
            <p class="lead">University of Mindanao Library Collaboration Spaces</p>
        </div>
    </section>

    <main class="container my-5">
        <div class="reservation-card card">
            <div class="card-body">
                <form action="student_confirm_reservation.php" method="POST">
                    <input type="hidden" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>">

                    <div class="mb-4">
                        <label class="form-label">Date of Use</label>
                        <div class="input-group">
                            <span class="input-group-text bg-um-red text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="reservation_date" required
                                min="<?= date('Y-m-d') ?>">
                        </div>
                        <small class="text-muted">Available dates up to 2 weeks in advance</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Time Slot</label>
                        <div class="row g-3">
                            <?php
                            $start = strtotime('8:00');
                            $end = strtotime('21:30');

                            while ($start < $end) {
                                if (date('H:i', $start) == '12:00') {
                                    $start = strtotime('12:30', $start);
                                    continue;
                                }

                                $end_time = strtotime('+1 hour', $start);
                                $time_slot = date('g:i A', $start) . ' - ' . date('g:i A', $end_time);

                                echo '
                                <div class="col-12 col-md-6">
                                    <div class="time-slot-card">
                                        <input type="radio" name="time_slot" id="slot_' . date('Hi', $start) . '" 
                                               value="' . $time_slot . '" class="d-none" required>
                                        <label for="slot_' . date('Hi', $start) . '" 
                                               class="d-block p-3 m-0 position-relative">
                                            ' . $time_slot . '
                                        </label>
                                    </div>
                                </div>';

                                $start = $end_time;
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Subject</label>
                            <select class="form-select" name="subject" required>
                                <option value="">Select Subject</option>
                                <option value="CSE 7">CSE 7 - CS Professional Elective 1</option>
                                <option value="CS 6">CS 6 - Algorithms and Complexity</option>
                                <option value="PAHF 4">PAHF 4 - Dance and Sports 2</option>
                                <option value="BSM 222">BSM 222 - Linear Algebra</option>
                                <option value="GE 8">GE 8 - Readings in Philippine History</option>
                                <option value="CST 5">CST 5 - CS Professional Track 2</option>
                                <option value="BSM 312">BSM 312 - Differential Equations</option>
                                <option value="GE 6">GE 6 - Rizal's Life and Works</option>
                                <option value="GE 11">GE 11 - The Entrepreneurial Mind</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Purpose</label>
                            <select class="form-select" name="purpose" required>
                                <option value="">Select Purpose</option>
                                <option value="GROUP STUDY">GROUP STUDY</option>
                                <option value="PROBLEM SOLVING">PROBLEM SOLVING</option>
                                <option value="PROJECT DISCUSSION">PROJECT DISCUSSION</option>
                                <option value="RESEARCH PAPER">RESEARCH PAPER</option>
                                <option value="REPORTING">REPORTING</option>
                                <option value="DISCUSSION">DISCUSSION</option>
                                <option value="CONDUCT REVIEW">CONDUCT REVIEW</option>
                                <option value="PREPARATION FOR EXAM">PREPARATION FOR EXAM</option>
                                <option value="PRACTICE FOR THESES/DISSERTATION DEFENSE">PRACTICE FOR
                                    THESES/DISSERTATION DEFENSE</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-um btn-lg">
                            <i class="fas fa-check-circle me-2"></i>Submit Reservation
                        </button>
                    </div>
                </form>
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