<?php
session_start();

include 'includes/header.php';
include 'includes/sidebar.php';

include 'includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$room_number = $_GET['room_number'] ?? '';
$date = $_GET['date'] ?? '';
$status = $_GET['status'] ?? '';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

$query = "
    SELECT b.id, r.room_number, b.date, b.timeslot, b.subject, b.purpose, b.status 
    FROM Bookings b
    JOIN Rooms r ON b.room_id = r.id
    WHERE 1=1
";
$params = [];

if (!empty($room_number)) {
    $query .= " AND r.room_number LIKE :room_number";
    $params[':room_number'] = "%$room_number%";
}

if (!empty($date)) {
    $query .= " AND b.date = :date";
    $params[':date'] = $date;
}

if (!empty($status)) {
    $query .= " AND b.status = :status";
    $params[':status'] = $status;
}

$query .= " ORDER BY b.date DESC, b.timeslot ASC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);

foreach ($params as $key => &$value) {
    $stmt->bindParam($key, $value, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalBookingsQuery = "SELECT COUNT(*) FROM Bookings b JOIN Rooms r ON b.room_id = r.id WHERE 1=1";

if (!empty($room_number)) {
    $totalBookingsQuery .= " AND r.room_number LIKE :room_number";
}

if (!empty($date)) {
    $totalBookingsQuery .= " AND b.date = :date";
}

if (!empty($status)) {
    $totalBookingsQuery .= " AND b.status = :status";
}

$totalStmt = $pdo->prepare($totalBookingsQuery);

foreach ($params as $key => &$value) {
    $totalStmt->bindParam($key, $value, PDO::PARAM_STR);
}

$totalStmt->execute();
$totalBookings = $totalStmt->fetchColumn();
$totalPages = ceil($totalBookings / $limit);
?>

<div class="main-content container-fluid">
    <h1>Booking Management</h1>

    <form method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="room_number" class="form-label">Room Number</label>
                <input type="text" name="room_number" id="room_number" class="form-control" placeholder="Search by room">
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 mt-4">Filter</button>
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
                <a class="page-link" href="?page=<?= $i ?>&room_number=<?= urlencode($room_number) ?>&date=<?= urlencode($date) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include 'includes/footer.php'; ?>
