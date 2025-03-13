<?php
session_start();

require_once '../includes/db_connection.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('User ID not provided.'); window.location.href='../user_management.php';</script>";
    exit();
}

$user_id = $_GET['id'];

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM Users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('User deleted successfully!'); window.location.href='../user_management.php';</script>";
    } else {
        echo "<script>alert('User not found or already deleted.'); window.location.href='../user_management.php';</script>";
    }
} else {
    echo "<script>alert('Error deleting user: " . $stmt->error . "'); window.location.href='../user_management.php';</script>";
}

$stmt->close();
$conn->close();
?>