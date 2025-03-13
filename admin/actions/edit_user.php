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

$user_id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';

if (empty($user_id) || empty($name) || empty($email) || empty($role)) {
    $_SESSION['error'] = "Please fill in all required fields.";
    header("Location: ../user_management.php");
    exit();
}

$stmt = $conn->prepare("
    UPDATE Users 
    SET name = ?, email = ?, role = ? 
    WHERE id = ?
");
$stmt->bind_param("sssi", $name, $email, $role, $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User updated successfully!";
} else {
    $_SESSION['error'] = "Error updating user: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: ../user_management.php");
exit();
?>