<?php
session_start();

include '../includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['add_name'];
    $email = $_POST['add_email'];
    $role = $_POST['add_role'];
    $password = $_POST['add_password'];

    if (!empty($name) && !empty($email) && !empty($role) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Users (name, email, role, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $role, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['success'] = "User added successfully!";
            header("Location: ../user_management.php");
            exit();
        } else {
            $_SESSION['error'] = "Error adding user: " . $stmt->error;
            header("Location: ../user_management.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: ../user_management.php");
        exit();
    }
}

$conn->close();
?>