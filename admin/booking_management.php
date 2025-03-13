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
    SELECT b.id, r.room_number, b.date, b.timeslot, b.subject, b.purpose, b.status, 
           u.name AS user_name, u.profile_image AS user_profile_image
    FROM Bookings b
    JOIN Rooms r ON b.room_id = r.id
    JOIN Users u ON b.user_id = u.id
    WHERE 1=1
";

if (!empty($room_number)) {
    $query .= " AND r.room_number LIKE ?";
}

if (!empty($date)) {
    $query .= " AND b.date = ?";
}

if (!empty($status)) {
    $query .= " AND b.status = ?";
}

$query .= " ORDER BY b.date DESC, b.timeslot ASC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

$types = '';
$params = [];

if (!empty($room_number)) {
    $types .= 's';
    $params[] = "%$room_number%";
}

if (!empty($date)) {
    $types .= 's';
    $params[] = $date;
}

if (!empty($status)) {
    $types .= 's';
    $params[] = $status;
}

$types .= 'ii'; 
$params[] = $limit;
$params[] = $offset;

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);

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
if (!$totalStmt) {
    die("Error preparing total bookings query: " . $conn->error);
}

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
                    <th>User</th>
                    <th style="width: 212px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['id']) ?></td>
                    <td><?= htmlspecialchars($booking['room_number']) ?></td>
                    <td><?= htmlspecialchars($booking['date']) ?></td>
                    <td><?= htmlspecialchars($booking['timeslot']) ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <?php if (!empty($booking['user_profile_image'])): ?>
                                <img src="../../images/profiles/<?= htmlspecialchars($booking['user_profile_image']) ?>" alt="Profile Image" width="40" height="40" class="rounded-circle me-2">
                            <?php else: ?>
                                <img src="../../images/profiles/default-profile.jpg" alt="Default Profile Image" width="40" height="40" class="rounded-circle me-2">
                            <?php endif; ?>
                            <span><?= htmlspecialchars($booking['user_name']) ?></span>
                        </div>
                    </td>
                    <td class="justify-content-center align-items-center">
                        <?php if ($booking['status'] == 'pending'): ?>
                            <a href="actions/approve_booking.php?id=<?= $booking['id'] ?>" class="btn btn-sm btn-success">Approve</a>
                            <a href="actions/reject_booking.php?id=<?= $booking['id'] ?>" class="btn btn-sm btn-danger">Reject</a>
                        <?php else: ?>
                            <span class="badge bg-<?= $booking['status'] == 'approved' ? 'success' : 'danger' ?>">
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        <?php endif; ?>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal<?= $booking['id'] ?>">
                            Details
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            <?php foreach ($bookings as $booking): ?>
            <div class="modal fade" id="detailsModal<?= $booking['id'] ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?= $booking['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsModalLabel<?= $booking['id'] ?>">Booking Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>Booking ID:</strong> <?= htmlspecialchars($booking['id']) ?>
                            </div>
                            <div class="mb-3">
                                <strong>Room Number:</strong> <?= htmlspecialchars($booking['room_number']) ?>
                            </div>
                            <div class="mb-3">
                                <strong>Date:</strong> <?= htmlspecialchars($booking['date']) ?>
                            </div>
                            <div class="mb-3">
                                <strong>Timeslot:</strong> <?= htmlspecialchars($booking['timeslot']) ?>
                            </div>
                            <div class="mb-3">
                                <strong>Subject:</strong> <?= htmlspecialchars($booking['subject']) ?>
                            </div>
                            <div class="mb-3">
                                <strong>Purpose:</strong> <?= htmlspecialchars($booking['purpose']) ?>
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <span class="badge bg-<?= $booking['status'] == 'approved' ? 'success' : ($booking['status'] == 'rejected' ? 'danger' : 'warning') ?>">
                                    <?= ucfirst($booking['status']) ?>
                                </span>
                            </div>
                            <div class="mb-3">
                                <strong>Booked By:</strong>
                                <div class="d-flex align-items-center mt-2">
                                    <?php if (!empty($booking['user_profile_image'])): ?>
                                        <img src="../../images/profiles/<?= htmlspecialchars($booking['user_profile_image']) ?>" alt="Profile Image" width="40" height="40" class="rounded-circle me-2">
                                    <?php else: ?>
                                        <img src="../../images/profiles/default-profile.jpg" alt="Default Profile Image" width="40" height="40" class="rounded-circle me-2">
                                    <?php endif; ?>
                                    <span><?= htmlspecialchars($booking['user_name']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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

            function debounce(func, delay) {
                let timeout;
                return function () {
                    clearTimeout(timeout);
                    timeout = setTimeout(func, delay);
                };
            }

            function submitForm() {
                filterForm.submit();
            }

            const debouncedSubmitForm = debounce(submitForm, 500);

            roomNumberInput.addEventListener('input', function () {
                const value = roomNumberInput.value.trim(); 
                if (value.length >= 3 || value.length === 0) {
                    debouncedSubmitForm();
                }
            });

            dateInput.addEventListener('change', submitForm);
            statusSelect.addEventListener('change', submitForm);
        });
    </script>

    <?php include 'includes/footer.php'; ?>
