<?php
session_start();

include '../includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['add_room_number'];
    $capacity = $_POST['add_capacity'];
    $equipment = $_POST['add_equipment'];
    $floor = $_POST['add_floor'];
    $description = $_POST['description'];

    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../images/rooms/";
        $file_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            if (move_uploaded_file($_FILES['image']["tmp_name"], $target_file)) {
                $image = $file_name;
            } else {
                echo "<script>alert('Error uploading image. Check directory permissions.'); window.location.href='../room_management.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.'); window.location.href='../room_management.php';</script>";
            exit();
        }
    }

    if (!empty($room_number) && !empty($capacity) && !empty($floor)) {
        $stmt = $conn->prepare("INSERT INTO Rooms (room_number, capacity, equipment, floor, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $room_number, $capacity, $equipment, $floor, $description, $image);

        if ($stmt->execute()) {
            echo "<script>alert('Room added successfully!'); window.location.href='../room_management.php';</script>";
        } else {
            echo "<script>alert('Error adding room: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }
}

$conn->close();
?>