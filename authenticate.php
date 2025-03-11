<?php
session_start();

$host = 'localhost';
$dbname = 'wbdv';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get form data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Please fill in all fields.";
    header("Location: login.php");
    exit();
}

// Fetch user from database
$stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify user and password
if ($user && password_verify($password, $user['password'])) {
    // Login successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];

    // Redirect based on role
    if ($user['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: student_home.php");
    }
    exit();
} else {
    // Login failed
    $_SESSION['login_error'] = "Invalid email or password.";
    header("Location: login.php");
    exit();
}