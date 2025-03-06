<?php
session_start();
// Authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get room number from URL parameter
$room_number = $_GET['room'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form - UM Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --um-red: #CC0000;
            --um-yellow: #FFD700;
        }

        body {
            background-image: url('images/reservation-bg.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .reservation-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            backdrop-filter: blur(5px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .reservation-card {
            border: 2px solid var(--um-red);
            border-radius: 15px;
            max-width: 800px;
            margin: 2rem auto;
        }

        .form-label {
            font-weight: bold;
            color: var(--um-red);
        }

        .btn-um {
            background-color: var(--um-yellow);
            color: var(--um-red);
            border: none;
            padding: 10px 30px;
            font-weight: bold;
            transition: transform 0.3s;
        }

        .btn-um:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
     <!-- Navigation -->
     <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #CC0000;">
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

    <main class="container mt-5 pt-4">
        <div class="reservation-card card">
            <div class="card-body">
                <h2 class="text-center mb-4">Room Reservation Form</h2>
                <h4 class="text-center mb-4">Room <?= htmlspecialchars($room_number) ?></h4>
                
                <form action="process_reservation.php" method="POST">
                    <input type="hidden" name="room_number" value="<?= htmlspecialchars($room_number) ?>">
                    
                    <!-- Date Selection -->
                    <div class="mb-4">
                        <label class="form-label">Date of Use</label>
                        <input type="date" class="form-control" name="reservation_date" required 
                               min="<?= date('Y-m-d') ?>">
                    </div>

                    <!-- Time Slot Selection -->
                    <div class="mb-4">
                        <label class="form-label">Time Slot</label>
                        <select class="form-select" name="time_slot" required>
                            <option value="">Select Time Slot</option>
                            <?php
                            // Generate time slots with break
                            $start = strtotime('08:00');
                            $end = strtotime('21:30');
                            
                            while($start < $end) {
                                $next = strtotime('+1 hour', $start);
                                
                                // Skip 12:00-12:30
                                if(date('H:i', $start) == '12:00') {
                                    $start = strtotime('12:30', $start);
                                    continue;
                                }
                                
                                $time_slot = date('g:i A', $start).' - '.date('g:i A', $next);
                                echo "<option value='$time_slot'>$time_slot</option>";
                                $start = $next;
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Subject Selection -->
                    <div class="mb-4">
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

                    <!-- Purpose Selection -->
                    <div class="mb-4">
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
                            <option value="PRACTICE FOR THESES/DISSERTATION DEFENSE">PRACTICE FOR THESES/DISSERTATION DEFENSE</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-um btn-lg">Submit Reservation</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>