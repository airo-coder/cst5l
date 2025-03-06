<?php
session_start();
// Temporary development bypass - remove these lines in production
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = "Test User";

$rooms = [
    // First Floor (4-6 Persons)
    [
        'number' => '101',
        'capacity' => '4-6 Persons',
        'equipment' => 'LCD Screen, Whiteboard',
        'floor' => '1st Floor',
        'description' => 'Ideal for small group discussions and brainstorming sessions'
    ],
    [
        'number' => '102',
        'capacity' => '4-6 Persons',
        'equipment' => 'Projector, Conference Table',
        'floor' => '1st Floor',
        'description' => 'Perfect for presentations and team meetings'
    ],
    [
        'number' => '103',
        'capacity' => '4-6 Persons',
        'equipment' => 'LCD Screen, Whiteboard',
        'floor' => '1st Floor',
        'description' => 'Compact space with collaborative technology'
    ],
    [
        'number' => '104',
        'capacity' => '4-6 Persons',
        'equipment' => 'Projector, Conference Table',
        'floor' => '1st Floor',
        'description' => 'Meeting room with professional presentation setup'
    ],
    [
        'number' => '105',
        'capacity' => '4-6 Persons',
        'equipment' => 'LCD Screen, Whiteboard',
        'floor' => '1st Floor',
        'description' => 'Interactive space for creative collaborations'
    ],
    [
        'number' => '106',
        'capacity' => '4-6 Persons',
        'equipment' => 'Projector, Conference Table',
        'floor' => '1st Floor',
        'description' => 'Tech-enabled collaboration space with smart board'
    ],

    // Second Floor (8-10 Persons)
    [
        'number' => '201',
        'capacity' => '8-10 Persons',
        'equipment' => 'Projector, Conference Table',
        'floor' => '2nd Floor',
        'description' => 'Large conference-style meeting room'
    ],
    [
        'number' => '202',
        'capacity' => '8-10 Persons',
        'equipment' => 'LCD Screen, Whiteboard',
        'floor' => '2nd Floor',
        'description' => 'Spacious collaborative environment with dual displays'
    ],
    [
        'number' => '203',
        'capacity' => '8-10 Persons',
        'equipment' => 'Projector, Conference Table',
        'floor' => '2nd Floor',
        'description' => 'Executive meeting room with video conferencing'
    ],
    [
        'number' => '204',
        'capacity' => '8-10 Persons',
        'equipment' => 'LCD Screen, Whiteboard',
        'floor' => '2nd Floor',
        'description' => 'Innovation lab with writable walls'
    ],
    [
        'number' => '205',
        'capacity' => '8-10 Persons',
        'equipment' => 'Projector, Conference Table',
        'floor' => '2nd Floor',
        'description' => 'Multi-purpose large group collaboration space'
    ],
    [
        'number' => '206',
        'capacity' => '8-10 Persons',
        'equipment' => 'LCD Screen, Whiteboard',
        'floor' => '2nd Floor',
        'description' => 'Flexible workshop space with movable furniture'
    ]
];
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
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
            box-shadow: 0 5px 15px rgba(204,0,0,0.2);
        }

        .footer {
            background: var(--um-red);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 5rem;
        }

        .wave-divider svg path {
            fill: rgba(255, 255, 255, 0.8);
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
            <h1 class="display-4 mb-4">Available Collaboration Rooms</h1>
            <p class="lead mb-4">Select your preferred space for academic collaboration</p>
        </div>
    </section>

    <main class="container my-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($rooms as $room): ?>
            <div class="col">
                <div class="card h-100 room-card">
                    <div class="floor-badge"><?= $room['floor'] ?></div>
                    <div class="capacity-badge"><?= $room['capacity'] ?></div>
                    <img src="images/rooms/room-<?= $room['number'] ?>.jpg" 
                         class="room-image" 
                         alt="Room <?= $room['number'] ?>">
                    <div class="card-body">
                        <h5 class="card-title">Room <?= $room['number'] ?></h5>
                        <div class="equipment-list mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-tools text-um-red me-2"></i>
                                <?= $room['equipment'] ?>
                            </div>
                        </div>
                        <p class="card-text text-muted small">
                            <?= $room['description'] ?>
                        </p>
                        <a href="student_reservation.php?room=<?= $room['number'] ?>" 
                           class="btn btn-um w-100">
                            Reserve Now <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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