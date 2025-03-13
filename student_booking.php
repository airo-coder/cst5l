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

$query = "SELECT * FROM rooms";
$result = $conn->query($query);

if ($result) {
    $rooms = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Error fetching rooms: " . $conn->error);
}

$conn->close();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms - UM Library</title>
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

        .room-card {
            border: 2px solid var(--um-red);
            border-radius: 15px;
            transition: all 0.3s;
            overflow: hidden;
            position: relative;
        }


        .wave-divider svg path {
            fill: rgba(255, 255, 255, 0.8);
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .room-image {
            height: 200px;
            object-fit: cover;
            border-bottom: 3px solid var(--um-red);
        }

        .capacity-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--um-red);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .floor-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--um-yellow);
            color: var(--um-red);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .btn-um {
            background-color: var(--um-yellow);
            color: var(--um-red);
            border: none;
            padding: 10px 25px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-um:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.2);
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
<?php include 'student_header.php'; ?>

<section class="hero-section">
    <div class="wave-divider">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M1200 0L0 0 892.25 104.14 1200 0z"></path>
        </svg>
    </div>
    <div class="container text-center">
        <h1 class="display-4 mb-4">Available Collaboration Rooms</h1>
        <p class="lead mb-4">Select your preferred space for academic collaboration</p>
    </div>
</section>

<main class="container my-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($rooms as $room): ?>
            <div class="col">
                <div class="card h-100 room-card">
                    <div class="floor-badge"><?= htmlspecialchars($room['floor']) ?></div>
                    <div class="capacity-badge"><?= htmlspecialchars($room['capacity']) ?> Persons</div>
                    <img src="images/rooms/room-<?= htmlspecialchars($room['room_number']) ?>.jpg" class="room-image"
                        alt="Room <?= htmlspecialchars($room['room_number']) ?>">
                    <div class="card-body">
                        <h5 class="card-title">Room <?= htmlspecialchars($room['room_number']) ?></h5>
                        <div class="equipment-list mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-tools text-um-red me-2"></i>
                                <?= htmlspecialchars($room['equipment']) ?>
                            </div>
                        </div>
                        <p class="card-text text-muted small">
                            <?= htmlspecialchars($room['description']) ?>
                        </p>
                        <a href="student_reservation.php?room=<?= htmlspecialchars($room['room_number']) ?>" class="btn btn-um w-100">
                            Reserve Now <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'student_footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>