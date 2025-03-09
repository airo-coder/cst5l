<?php
session_start();

require_once '../includes/db_connection.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('User ID not provided.'); window.location.href='../user_management.php';</script>";
    exit();
}

$user_id = $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM Users WHERE id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('User deleted successfully!'); window.location.href='../user_management.php';</script>";
    } else {
        echo "<script>alert('User not found or already deleted.'); window.location.href='../user_management.php';</script>";
    }
} catch (PDOException $e) {
    echo "<script>alert('Error deleting user: " . $e->getMessage() . "'); window.location.href='../user_management.php';</script>";
}
?>