<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$room_id = $_POST['id'] ?? '';
$room_number = $_POST['room_number'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$equipment = $_POST['equipment'] ?? '';
$floor = $_POST['floor'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($room_id) || empty($room_number) || empty($capacity) || empty($floor)) {
    die("Please fill in all required fields.");
}

$current_image = null;
$fetch_stmt = $conn->prepare("SELECT image FROM Rooms WHERE id = ?");
$fetch_stmt->bind_param("i", $room_id);
$fetch_stmt->execute();
$fetch_stmt->bind_result($current_image);
$fetch_stmt->fetch();
$fetch_stmt->close();

$image = $current_image; 

if (!empty($_FILES['image']['name'])) {
    $target_dir = "../../images/rooms/";
    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (in_array($imageFileType, ["jpg", "jpeg", "png"])) {
        if (move_uploaded_file($_FILES['image']["tmp_name"], $target_file)) {
            if ($current_image && file_exists($target_dir . $current_image)) {
                unlink($target_dir . $current_image);
            }
            $image = $file_name;
        } else {
            die("Error uploading image.");
        }
    } else {
        die("Only JPG, JPEG, and PNG files are allowed.");
    }
}

$query = "UPDATE Rooms 
          SET room_number = ?, capacity = ?, equipment = ?, 
              floor = ?, description = ?, image = ?
          WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sissssi", $room_number, $capacity, $equipment, $floor, $description, $image, $room_id);

if ($stmt->execute()) {
    echo "<script>alert('Room added successfully!'); window.location.href='../room_management.php';</script>";
} else {
    echo "<script>alert('Error adding room: " . $stmt->error . "');</script>";
}

$stmt->close();
$conn->close();

header("Location: ../room_management.php");
exit();
?>