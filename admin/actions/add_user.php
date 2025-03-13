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
    
    $emailRegex = '/^[a-z]\.([a-z]+)\.(\d{6})@umindanao\.edu\.ph$/';
    if (!preg_match($emailRegex, $email)) {
        $_SESSION['error'] = "Email must be in the format: [a].[name].[6 digits]@umindanao.edu.ph";
        header("Location: ../user_management.php");
        exit();
    }

    $profile_image = null;
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../../images/profiles/";
        $file_name = basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            if (move_uploaded_file($_FILES['profile_image']["tmp_name"], $target_file)) {
                $profile_image = $file_name;
            } else {
                $_SESSION['error'] = "Error uploading profile image.";
                header("Location: ../user_management.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Only JPG, JPEG, and PNG files are allowed.";
            header("Location: ../user_management.php");
            exit();
        }
    }

    if (!empty($name) && !empty($email) && !empty($role) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Users (name, email, role, password, profile_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $role, $hashedPassword, $profile_image);

        if ($stmt->execute()) {
            $_SESSION['success'] = "User added successfully!";
        } else {
            $_SESSION['error'] = "Error adding user: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill in all required fields.";
    }

    header("Location: ../user_management.php");
    exit();
}

$conn->close();
?>