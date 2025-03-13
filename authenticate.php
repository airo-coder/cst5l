<?php
session_start();

include 'admin/includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Please fill in all fields.";
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: student_home.php");
    }
    exit();
} else {
    $_SESSION['login_error'] = "Invalid email or password.";
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>