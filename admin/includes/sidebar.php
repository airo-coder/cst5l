<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    
<div class="sidebar" style="width: 250px; min-height: 100vh; padding: 20px;">
    <ul class="nav flex-column">

        <li class="nav-item">
            <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
            <a class="nav-link" href="index.php">Dashboard</a>
        </li>

        <li class="nav-item">
            <span class="icon"><ion-icon name="pencil-outline"></ion-icon></span>
            <a class="nav-link" href="room_management.php">Room Management</a>
        </li>

        <li class="nav-item">
            <span class="icon"><ion-icon name="book-outline"></ion-icon></span>
            <a class="nav-link" href="booking_management.php">Booking Management</a>
        </li>

        <li class="nav-item">
            <span class="icon"><ion-icon name="people-outline"></ion-icon></span>
            <a class="nav-link" href="user_management.php">User Management</a>
        </li>

        <li class="nav-item">
            <span class="icon"><ion-icon name="pencil-outline"></ion-icon></span>
            <a class="nav-link" href="reports.php">Reports</a>
        </li>

    </ul>
 
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>