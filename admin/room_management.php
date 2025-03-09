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

$floor = $_GET['floor'] ?? '';
$capacity = $_GET['capacity'] ?? '';
$equipment = $_GET['equipment'] ?? '';

$page = $_GET['page'] ?? 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM Rooms WHERE 1=1";
$params = [];

if (!empty($floor)) {
    $query .= " AND floor LIKE :floor";
    $params['floor'] = "%$floor%";
}

if (!empty($capacity)) {
    $query .= " AND capacity = :capacity";
    $params['capacity'] = $capacity;
}

if (!empty($equipment)) {
    $query .= " AND equipment LIKE :equipment";
    $params['equipment'] = "%$equipment%";
}

$query .= " ORDER BY room_number ASC LIMIT :limit OFFSET :offset";
$params['limit'] = $limit;
$params['offset'] = $offset;

$stmt = $pdo->prepare($query);
foreach ($params as $key => &$value) {
    $stmt->bindParam($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRoomsQuery = "SELECT COUNT(*) FROM Rooms WHERE 1=1";
if (!empty($floor)) {
    $totalRoomsQuery .= " AND floor LIKE :floor";
}
if (!empty($capacity)) {
    $totalRoomsQuery .= " AND capacity = :capacity";
}
if (!empty($equipment)) {
    $totalRoomsQuery .= " AND equipment LIKE :equipment";
}

$totalStmt = $pdo->prepare($totalRoomsQuery);
if (!empty($floor)) {
    $totalStmt->bindParam(':floor', $floor);
}
if (!empty($capacity)) {
    $totalStmt->bindParam(':capacity', $capacity);
}
if (!empty($equipment)) {
    $totalStmt->bindParam(':equipment', $equipment);
}
$totalStmt->execute();
$totalRooms = $totalStmt->fetchColumn();
$totalPages = ceil($totalRooms / $limit);
?>

<div class="main-content container-fluid">
    <h1>Room Management</h1>

    <form method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="floor" class="form-label">Floor</label>
                <input type="text" name="floor" id="floor" class="form-control" placeholder="Search by floor">
            </div>
            <div class="col-md-3">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="number" name="capacity" id="capacity" class="form-control" placeholder="Search by capacity">
            </div>
            <div class="col-md-3">
                <label for="equipment" class="form-label">Equipment</label>
                <input type="text" name="equipment" id="equipment" class="form-control" placeholder="Search by equipment">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 mt-4">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Room Number</th>
                <th>Capacity</th>
                <th>Equipment</th>
                <th>Floor</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?= htmlspecialchars($room['room_number']) ?></td>
                <td><?= htmlspecialchars($room['capacity']) ?></td>
                <td><?= htmlspecialchars($room['equipment']) ?></td>
                <td><?= htmlspecialchars($room['floor']) ?></td>
                <td><?= htmlspecialchars($room['description']) ?></td>
                <td>
                    <a href="#editRoomModal" class="btn btn-sm btn-warning edit-room-btn" data-id="<?= $room['id'] ?>">Edit</a>
                    <a href="actions/delete_room.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav aria-label="Rooms Pagination" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&floor=<?= $floor ?>&capacity=<?= $capacity ?>&equipment=<?= $equipment ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <a href="actions/add_room.php" class="btn btn-primary">Add New Room</a>

<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoomForm" method="POST" action="actions/update_room.php">
                    <input type="hidden" name="id" id="editRoomId">
                    <div class="mb-3">
                        <label for="editRoomNumber" class="form-label">Room Number</label>
                        <input type="text" name="room_number" id="editRoomNumber" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCapacity" class="form-label">Capacity</label>
                        <input type="number" name="capacity" id="editCapacity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEquipment" class="form-label">Equipment</label>
                        <input type="text" name="equipment" id="editEquipment" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editFloor" class="form-label">Floor</label>
                        <input type="text" name="floor" id="editFloor" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea name="description" id="editDescription" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('.edit-room-btn').on('click', function () {
        var roomId = $(this).data('id'); 
        var row = $(this).closest('tr');
        var roomNumber = row.find('td:eq(0)').text();
        var capacity = row.find('td:eq(1)').text();
        var equipment = row.find('td:eq(2)').text();
        var floor = row.find('td:eq(3)').text();
        var description = row.find('td:eq(4)').text();

        $('#editRoomId').val(roomId);
        $('#editRoomNumber').val(roomNumber);
        $('#editCapacity').val(capacity);
        $('#editEquipment').val(equipment);
        $('#editFloor').val(floor);
        $('#editDescription').val(description);

        $('#editRoomModal').modal('show');
    });

    $('#editRoomForm').on('submit', function (e) {
        e.preventDefault(); 

        var formData = $(this).serialize(); 

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                alert('Room updated successfully!');
                $('#editRoomModal').modal('hide');
                location.reload();
            },
            error: function () {
                alert('Failed to update room.');
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>