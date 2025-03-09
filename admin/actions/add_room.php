<?php
// Include header and sidebar
include '../includes/header.php';
include '../includes/sidebar.php';

// Database connection
$host = 'localhost';
$dbname = 'wbdv';
$username = 'root';
$password = 'admin123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];
    $equipment = $_POST['equipment'];
    $floor = $_POST['floor'];
    $description = $_POST['description'];

    // Validate input
    if (!empty($room_number) && !empty($capacity) && !empty($floor)) {
        try {
            // Insert room into the database
            $stmt = $pdo->prepare("INSERT INTO Rooms (room_number, capacity, equipment, floor, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$room_number, $capacity, $equipment, $floor, $description]);

            // Redirect with success message
            echo "<script>alert('Room added successfully!'); window.location.href='../room_management.php';</script>";
        } catch (PDOException $e) {
            // Handle database errors
            echo "<script>alert('Error adding room: " . $e->getMessage() . "');</script>";
        }
    } else {
        // Handle validation errors
        echo "<script>alert('Please fill in all required fields.');</script>";
    }
}
?>

<div class="main-content container-fluid">
    <h1>Add New Room</h1>
    <form action="add_room.php" method="POST">
        <div class="form-group">
            <label for="room_number">Room Number</label>
            <input type="text" class="form-control" id="room_number" name="room_number" required>
        </div>
        <div class="form-group">
            <label for="capacity">Capacity</label>
            <input type="text" class="form-control" id="capacity" name="capacity" required>
        </div>
        <div class="form-group">
            <label for="equipment">Equipment</label>
            <input type="text" class="form-control" id="equipment" name="equipment">
        </div>
        <div class="form-group">
            <label for="floor">Floor</label>
            <input type="text" class="form-control" id="floor" name="floor" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Room</button>
        <a href="../room_management.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>