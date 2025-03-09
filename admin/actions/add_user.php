<?php
session_start(); // Start session for flash messages

// Include the database connection file
include '../includes/db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password']; // Get the password from the form

    // Validate input
    if (!empty($name) && !empty($email) && !empty($role) && !empty($password)) {
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $stmt = $pdo->prepare("INSERT INTO Users (name, email, role, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $role, $hashedPassword]);

            // Redirect with success message
            $_SESSION['success'] = "User added successfully!";
            header("Location: ../user_management.php");
            exit();
        } catch (PDOException $e) {
            // Handle database errors
            $_SESSION['error'] = "Error adding user: " . $e->getMessage();
            header("Location: ../user_management.php");
            exit();
        }
    } else {
        // Handle validation errors
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: ../user_management.php");
        exit();
    }
}
?>