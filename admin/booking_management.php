<?php
session_start();

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$room_number = $_GET['room_number'] ?? '';
$date = $_GET['date'] ?? '';
$status = $_GET['status'] ?? 'pending'; 


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$query = "
    SELECT b.id, r.room_number, b.date, b.timeslot, b.subject, b.purpose, b.status 
    FROM Bookings b
    JOIN Rooms r ON b.room_id = r.id
    WHERE 1=1
";
$types = '';
$params = [];

if (!empty($room_number)) {
    $query .= " AND r.room_number LIKE ?";
    $types .= 's';
    $params[] = "%$room_number%";
}

if (!empty($date)) {
    $query .= " AND b.date = ?";
    $types .= 's';
    $params[] = $date;
}

if (!empty($status)) {
    $query .= " AND b.status = ?";
    $types .= 's';
    $params[] = $status;
}

$query .= " ORDER BY b.date DESC, b.timeslot ASC LIMIT ? OFFSET ?";
$types .= 'ii';
$params[] = $limit;
$params[] = $offset;

$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Error preparing query: " . $conn->error);
}

$totalBookingsQuery = "SELECT COUNT(*) FROM Bookings b JOIN Rooms r ON b.room_id = r.id WHERE 1=1";

if (!empty($room_number)) {
    $totalBookingsQuery .= " AND r.room_number LIKE ?";
}

if (!empty($date)) {
    $totalBookingsQuery .= " AND b.date = ?";
}

if (!empty($status)) {
    $totalBookingsQuery .= " AND b.status = ?";
}

$totalStmt = $conn->prepare($totalBookingsQuery);
if ($totalStmt) {
    $totalTypes = '';
    $totalParams = [];

    if (!empty($room_number)) {
        $totalTypes .= 's';
        $totalParams[] = "%$room_number%";
    }
    if (!empty($date)) {
        $totalTypes .= 's';
        $totalParams[] = $date;
    }
    if (!empty($status)) {
        $totalTypes .= 's';
        $totalParams[] = $status;
    }

    if (!empty($totalTypes)) {
        $totalStmt->bind_param($totalTypes, ...$totalParams);
    }

    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalBookings = $totalResult->fetch_row()[0];
    $totalPages = ceil($totalBookings / $limit);
} else {
    die("Error preparing total bookings query: " . $conn->error);
}

$stmt->close();
$totalStmt->close();
$conn->close();
?>

<div class="main-content container-fluid">
    <h1>Booking Management</h1>

    <form method="GET" class="mb-4" id="filterForm">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="room_number" class="form-label">Room Number</label>
                <input type="text" name="room_number" id="room_number" class="form-control" placeholder="Search by room" value="<?= htmlspecialchars($room_number ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" value="<?= htmlspecialchars($date ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="pending" <?= ($status ?? 'pending') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= ($status ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= ($status ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    <option value="" <?= empty($status) ? 'selected' : '' ?>>All</option>
                </select>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Booking ID</th>
                <th>Room Number</th>
                <th>Date</th>
                <th>Timeslot</th>
                <th>Subject</th>
                <th>Purpose</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?= htmlspecialchars($booking['id']) ?></td>
                <td><?= htmlspecialchars($booking['room_number']) ?></td>
                <td><?= htmlspecialchars($booking['date']) ?></td>
                <td><?= htmlspecialchars($booking['timeslot']) ?></td>
                <td><?= htmlspecialchars($booking['subject']) ?></td>
                <td><?= htmlspecialchars($booking['purpose']) ?></td>
                <td>
                    <?php if ($booking['status'] == 'pending'): ?>
                        <a href="actions/approve_booking.php?id=<?= $booking['id'] ?>" class="btn btn-sm btn-success">Approve</a>
                        <a href="actions/reject_booking.php?id=<?= $booking['id'] ?>" class="btn btn-sm btn-danger">Reject</a>
                    <?php else: ?>
                        <span class="badge bg-<?= $booking['status'] == 'approved' ? 'success' : 'danger' ?>">
                            <?= ucfirst($booking['status']) ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav aria-label="Bookings Pagination" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&room_number=<?= urlencode($room_number ?? '') ?>&date=<?= urlencode($date ?? '') ?>&status=<?= urlencode($status ?? '') ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const roomNumberInput = document.getElementById('room_number');
    const dateInput = document.getElementById('date');
    const statusSelect = document.getElementById('status');

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function submitForm() {
        filterForm.submit();
    }

    const debouncedSubmitForm = debounce(submitForm, 300);

    roomNumberInput.addEventListener('input', function () {
        const value = roomNumberInput.value.trim(); 
        if (value.length >= 3) {
            debouncedSubmitForm();
        }
    });
    dateInput.addEventListener('change', submitForm);
    statusSelect.addEventListener('change', submitForm);
});
</script>


<?php include 'includes/footer.php'; ?>
