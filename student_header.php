<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add your CSS links here if needed -->
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
                        <a class="nav-link" href="admin/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>