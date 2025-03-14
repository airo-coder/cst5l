<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'student_header.php';
include 'admin/includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$date = $_GET['date'] ?? '';
$status = $_GET['status'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$query = "SELECT b.id, r.room_number, b.date, b.timeslot, b.subject, b.purpose, b.status, b.created_at
          FROM Bookings b
          JOIN Rooms r ON b.room_id = r.id
          WHERE b.user_id = ?";

$params = [$_SESSION['user_id']];
$types = "i";

if (!empty($date)) {
    $query .= " AND b.date = ?";
    $params[] = $date;
    $types .= "s";
}

if (!empty($status)) {
    $query .= " AND b.status = ?";
    $params[] = $status;
    $types .= "s";
}

$query .= " ORDER BY b.created_at DESC, b.timeslot ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);

$totalQuery = "SELECT COUNT(*) FROM Bookings WHERE user_id = ?";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param("i", $_SESSION['user_id']);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalBookings = $totalResult->fetch_row()[0];
$totalPages = ceil($totalBookings / $limit);

$stmt->close();
$totalStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - UM Library</title>
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

        .booking-card {
            border: 2px solid var(--um-red);
            border-radius: 15px;
            transition: all 0.3s;
            overflow: hidden;
            position: relative;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--um-red);
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .filter-card {
            border: 2px solid var(--um-red);
            border-radius: 15px;
            background: rgba(255,255,255,0.9);
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

        .hero-section {
            background: linear-gradient(135deg, var(--um-red), var(--um-yellow));
            padding: 100px 0;
            color: white;
            position: relative;
            overflow: hidden;
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
    <section class="hero-section">
        <div class="wave-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M1200 0L0 0 892.25 104.14 1200 0z"></path>
            </svg>
        </div>
        <div class="container text-center">
            <h1 class="display-4 mb-4">My Bookings</h1>
            <p class="lead mb-4">View and manage your room reservations</p>
        </div>
    </section>

    <main class="container my-5">
        <div class="filter-card card mb-5 p-4">
            <form method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Date</label>
                        <input type="date" name="date" class="form-control" 
                            value="<?= htmlspecialchars($date) ?>" id="filterDate">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" id="filterStatus">
                            <option value="">All Statuses</option>
                            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($bookings as $booking): ?>
            <div class="col">
                <div class="booking-card card h-100">
                    <div class="status-badge bg-<?= $booking['status'] === 'approved' ? 'success' : 
                                               ($booking['status'] === 'pending' ? 'warning' : 'danger') ?>">
                        <?= ucfirst($booking['status']) ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-door-open me-2"></i>
                            Room <?= htmlspecialchars($booking['room_number']) ?>
                        </h5>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-day me-2 text-um-red"></i>
                                <?= date('M j, Y', strtotime($booking['date'])) ?>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock me-2 text-um-red"></i>
                                <?= htmlspecialchars($booking['timeslot']) ?>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-book me-2 text-um-red"></i>
                                <?= htmlspecialchars($booking['subject']) ?>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bullseye me-2 text-um-red"></i>
                                <?= htmlspecialchars($booking['purpose']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($bookings)): ?>
        <div class="booking-card text-center p-5 mt-4">
            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
            <h5 class="text-muted">No bookings found</h5>
            <a href="student_booking.php" class="btn btn-um mt-3">
                <i class="fas fa-plus me-2"></i>Make a Booking
            </a>
        </div>
        <?php endif; ?>

        <?php if ($totalPages > 1): ?>
        <nav aria-label="Bookings navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                    <a class="page-link" 
                       href="?page=<?= $i ?>&date=<?= urlencode($date) ?>&status=<?= urlencode($status) ?>"
                       style="<?= $page == $i ? 'background-color: var(--um-red); border-color: var(--um-red);' : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </main>

    <?php include 'student_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script> 
    const filterForm = document.getElementById('filterForm');
    const filterDate = document.getElementById('filterDate');
    const filterStatus = document.getElementById('filterStatus');

    filterDate.addEventListener('change', () => {
        filterForm.submit(); 
    });

    filterStatus.addEventListener('change', () => {
        filterForm.submit();
    });
</script>
</body>
</html>