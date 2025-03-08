<?php
if (!isset($_GET['id'])) {
    echo "<script>alert('User ID not provided.'); window.location.href='../user_management.php';</script>";
    exit();
}

$user_id = $_GET['id'];

$conn = new mysqli('localhost', 'username', 'password', 'database_name');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "<script>alert('User deleted successfully!'); window.location.href='../user_management.php';</script>";
} else {
    echo "<script>alert('Error deleting user: " . $stmt->error . "'); window.location.href='../user_management.php';</script>";
}

$stmt->close();
$conn->close();
?>