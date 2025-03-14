<?php
include 'admin/includes/db_connection.php';

// Get current user's profile data
$user_id = $_SESSION['user_id'];
$query = "SELECT name, profile_image FROM Users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Set default image if no profile image exists
$profile_image = !empty($user['profile_image'])
    ? 'images/profiles/' . $user['profile_image']
    : 'images/profiles/default-profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--um-red);">
        <div class="container">
            <a class="navbar-brand" href="student_home.php" style="display: flex; align-items: center;">
                <img src="images/um-logo.png" alt="UM Logo" height="40">
                <span class="ms-2">Collaboration Room Reservation</span>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'student_home.php' ? 'active' : '' ?>"
                            href="student_home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'my_bookings.php' ? 'active' : '' ?>"
                            href="my_bookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a style="margin-right: 20px;"  class="nav-link" href="admin/logout.php">Logout</a>
                    </li>
                    <li>
                        <div class="d-flex align-items-center">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none" 
                           id="">
                            <img src="<?= $profile_image ?>" 
                                 alt="Profile Image" 
                                 width="40" 
                                 height="40" 
                                 class="rounded-circle me-2">
                            <span><?= htmlspecialchars($user['name']) ?></span>
                        </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>