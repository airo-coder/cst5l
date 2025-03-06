<?php
session_start();
// Temporary development bypass - remove in production
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = "Test User";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$room_number = $_GET['room'] ?? '';
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

        body {
            background-image: linear-gradient(rgba(240, 240, 255, 0.9   ), rgba(255, 255, 255, 0.9)), url('images/reservation-bg.png');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }

        .reservation-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .header-banner {
            background: linear-gradient(135deg, var(--um-red), var(--um-yellow));
        }

        .time-slot-card {
            border: 2px solid var(--um-red);
            border-radius: 10px;
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .time-slot-card:hover {
            background-color: rgba(204, 0, 0, 0.05);
        }

        .time-slot-card input[type="radio"]:checked + label {
            background-color: var(--um-red);
            color: white;
        }

        .time-slot-card input[type="radio"]:checked + label::after {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        .form-control:focus {
            border-color: var(--um-red);
            box-shadow: 0 0 0 0.25rem rgba(204, 0, 0, 0.25);
        }

        .select-arrow {
            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='%23cc0000' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") no-repeat right 0.75rem center/16px 12px;
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
            background-color: #ffcc00;
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

    <div class="header-banner text-white py-4 mb-5">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">Room Reservation System</h1>
            <p class="lead">University of Mindanao Library Collaboration Spaces</p>
        </div>
    </div>

    <main class="container mb-5">
        <div class="reservation-container p-4">
            <h2 class="text-center mb-4">Reservation Form <span class="badge bg-um-red">Room <?= htmlspecialchars($room_number) ?></span></h2>
            
            <form action="student_confirm_reservation.php" method="POST">
                <input type="hidden" name="room_number" value="<?= htmlspecialchars($room_number) ?>">

                <!-- Date Selection -->
                <div class="mb-4">
                    <label class="form-label">Date of Use</label>
                    <div class="input-group">
                        <span class="input-group-text bg-um-red text-white">
                            <i class="fas fa-calendar-alt" style="color: red"></i>
                        </span>
                        <input type="date" class="form-control" name="reservation_date" required 
                               min="<?= date('Y-m-d') ?>">
                    </div>
                    <small class="text-muted">Available dates up to 2 weeks in advance</small>
                </div>

                <!-- Enhanced Time Slot Grid -->
                <div class="mb-4">
                    <label class="form-label">Time Slot</label>
                    <div class="row g-3">
                        <?php
                        $start = strtotime('8:00');
                        $end = strtotime('21:30');
                        
                        while($start < $end) {
                            // Skip lunch break
                            if(date('H:i', $start) == '12:00') {
                                $start = strtotime('12:30', $start);
                                continue;
                            }
                            
                            $end_time = strtotime('+1 hour', $start);
                            $time_slot = date('g:i A', $start).' - '.date('g:i A', $end_time);
                            
                            echo '
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="time-slot-card">
                                    <input type="radio" name="time_slot" id="slot_'.date('Hi', $start).'" 
                                           value="'.$time_slot.'" class="d-none" required>
                                    <label for="slot_'.date('Hi', $start).'" 
                                           class="d-block p-3 m-0 position-relative">
                                        '.$time_slot.'
                                    </label>
                                </div>
                            </div>';
                            
                            $start = $end_time;
                        }
                        ?>
                    </div>
                </div>

                <!-- Subject Selection -->
                <div class="mb-4">
                    <label class="form-label">Subject</label>
                    <div class="input-group">
                        <span class="input-group-text bg-um-red text-white">
                            <i class="fas fa-book" style="color: red"></i>
                        </span>
                        <select class="form-select select-arrow" name="subject" required>
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
                </div>

                <!-- Purpose Selection -->
                <div class="mb-4">
                    <label class="form-label">Purpose</label>
                    <div class="input-group">
                        <span class="input-group-text bg-um-red text-white">
                            <i class="fas fa-bullseye" style="color: red"></i>
                        </span>
                        <select class="form-select select-arrow" name="purpose" required>
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
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-um btn-lg">
                        <i class="fas fa-check-circle me-2"></i>Submit Reservation
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 