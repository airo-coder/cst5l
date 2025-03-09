<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
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

try {
    $stmt = $pdo->prepare("
        UPDATE Users 
        SET name = :name, email = :email, role = :role 
        WHERE id = :id
    ");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'role' => $role,
        'id' => $user_id
    ]);

    $_SESSION['success'] = "User updated successfully!";
} catch (PDOException $e) {
    $_SESSION['error'] = "Error updating user: " . $e->getMessage();
}

header("Location: ../user_management.php");
exit();
?>