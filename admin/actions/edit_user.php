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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

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

    if (empty($user_id) || empty($name) || empty($email) || empty($role)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: ../user_management.php");
        exit();
    }

    $query = "UPDATE Users SET name = ?, email = ?, role = ?";
    if ($profile_image) {
        $query .= ", profile_image = ?";
    }
    $query .= " WHERE id = ?";

    $stmt = $conn->prepare($query);
    if ($profile_image) {
        $stmt->bind_param("ssssi", $name, $email, $role, $profile_image, $user_id);
    } else {
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "User updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../user_management.php");
    exit();
}
?>