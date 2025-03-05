<?php
session_start();
// Temporary development bypass - remove these lines in production
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = "Test User";

$rooms = [
    // First Floor (4-6 Persons)
    [
        'number' => '101', 'capacity' => '4-6 Persons', 
        'equipment' => 'LCD Screen, Whiteboard', 'floor' => '1st Floor',
        'description' => 'Ideal for small group discussions and brainstorming sessions'
    ],
    [
        'number' => '102', 'capacity' => '4-6 Persons',
        'equipment' => 'Projector, Conference Table', 'floor' => '1st Floor',
        'description' => 'Perfect for presentations and team meetings'
    ],
    [
        'number' => '103', 'capacity' => '4-6 Persons',
        'equipment' => 'LCD Screen, Whiteboard', 'floor' => '1st Floor',
        'description' => 'Compact space with collaborative technology'
    ],
    [
        'number' => '104', 'capacity' => '4-6 Persons',
        'equipment' => 'Projector, Conference Table', 'floor' => '1st Floor',
        'description' => 'Meeting room with professional presentation setup'
    ],
    [
        'number' => '105', 'capacity' => '4-6 Persons',
        'equipment' => 'LCD Screen, Whiteboard', 'floor' => '1st Floor',
        'description' => 'Interactive space for creative collaborations'
    ],
    // Second Floor (8-10 Persons)
    [
        'number' => '201', 'capacity' => '8-10 Persons',
        'equipment' => 'Projector, Conference Table', 'floor' => '2nd Floor',
        'description' => 'Large conference-style meeting room'
    ],
    [
        'number' => '202', 'capacity' => '8-10 Persons',
        'equipment' => 'LCD Screen, Whiteboard', 'floor' => '2nd Floor',
        'description' => 'Spacious collaborative environment with dual displays'
    ],
    [
        'number' => '203', 'capacity' => '8-10 Persons',
        'equipment' => 'Projector, Conference Table', 'floor' => '2nd Floor',
        'description' => 'Executive meeting room with video conferencing'
    ],
    [
        'number' => '204', 'capacity' => '8-10 Persons',
        'equipment' => 'LCD Screen, Whiteboard', 'floor' => '2nd Floor',
        'description' => 'Innovation lab with writable walls'
    ],
    [
        'number' => '205', 'capacity' => '8-10 Persons',
        'equipment' => 'Projector, Conference Table', 'floor' => '2nd Floor',
        'description' => 'Multi-purpose large group collaboration space'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Collaboration Rooms - UM Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --um-red: #CC0000;
            --um-yellow: #FFD700;
        }

        .room-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 2px solid var(--um-red);
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .room-image {
            height: 200px;
            object-fit: cover;
            border-bottom: 3px solid var(--um-red);
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
            background-color: #ffcc00;
        }

        .badge-um {
            background-color: var(--um-red);
            color: white;
            font-size: 0.9em;
        }

        .equipment-list {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 1rem;
        }

        .navbar-custom {
            background-color: var(--um-red) !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="student_home.php" style="display: flex; align-items: center;">
                <img src="images/um-logo.png" alt="UM Logo" height="40">
                <span class="ms-2">Collaboration Room Reservation</span>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
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

    <main class="container mt-5 pt-4">
        <h2 class="text-center mb-4" style="color: var(--um-red);">Available Collaboration Rooms</h2>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($rooms as $room): ?>
            <div class="col">
                <div class="card h-100 room-card">
                    <img src="images/rooms/room-<?= $room['number'] ?>.jpg" 
                         class="room-image" 
                         alt="Room <?= $room['number'] ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0" style="color: var(--um-red);">Room <?= $room['number'] ?></h5>
                            <span class="badge badge-um rounded-pill"><?= $room['capacity'] ?></span>
                        </div>
                        
                        <div class="equipment-list mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <?= $room['floor'] ?>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-tools me-2"></i>
                                <?= $room['equipment'] ?>
                            </div>
                        </div>
                        
                        <p class="card-text text-muted small">
                            <?= $room['description'] ?>
                        </p>
                        
                        <button class="btn btn-um w-100" 
                                data-bs-toggle="modal" 
                                data-bs-target="#bookingModal"
                                data-room="<?= $room['number'] ?>">
                            Reserve Now
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Booking Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reserve Room <span id="roomNumber"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="" class="img-fluid rounded-3" id="modalRoomImage" alt="Room Image">
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted">Select your preferred date and time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter Fix
        document.addEventListener('DOMContentLoaded', function() {
            const bookingModal = document.getElementById('bookingModal');
            bookingModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const roomNumber = button.getAttribute('data-room');
                const roomImg = button.closest('.card').querySelector('.room-image').src;
                
                document.getElementById('roomNumber').textContent = roomNumber;
                document.getElementById('modalRoomImage').src = roomImg;
            });
        });
    </script>
</body>
</html>