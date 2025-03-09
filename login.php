<?php
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
$error = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UM Library - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --um-red: #CC0000;
            --um-yellow: #FFD700;
        }

        .login-wrapper {
            min-height: 100vh;
            background: rgba(0, 0, 0, 0.4) url('images/login.png') center/cover;
            display: flex;
            align-items: center;
            position: relative;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 400px;
            margin: 0 auto;
        }

        .login-header {
            background: var(--um-red);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .login-header img {
            height: 60px;
            margin-bottom: 1rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--um-red);
            box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.2);
        }

        .input-icon {
            background: var(--um-red);
            color: white;
            min-width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px 0 0 8px;
        }

        .btn-um {
            background: var(--um-yellow);
            color: var(--um-red);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-um:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.15);
        }

        .auth-links a {
            color: var(--um-red);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="container">
            <div class="login-card">
                <div class="login-header">
                    <img src="images/um-logo.png" alt="UM Logo">
                    <h3 class="mb-0">Collaboration Room Access</h3>
                </div>
                
                <div class="login-body">
                    <?php if($error): ?>
                    <div class="alert alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="authenticate.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">UM Email</label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" name="email" placeholder="name@umindanao.edu.ph" required>
                            </div>
                        </div>

    <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-icon">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password" class="form-control" name="password" required>
        </div>
    </div>

    <button type="submit" class="btn btn-um w-100 mb-3">
        <i class="fas fa-sign-in-alt me-2"></i>Sign In
    </button>
</form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UM Library - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --um-red: #CC0000;
            --um-yellow: #FFD700;
        }

        .login-wrapper {
            min-height: 100vh;
            background: rgba(0, 0, 0, 0.4) url('images/login.png') center/cover;
            display: flex;
            align-items: center;
            position: relative;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 400px;
            margin: 0 auto;
        }

        .login-header {
            background: var(--um-red);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .login-header img {
            height: 60px;
            margin-bottom: 1rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--um-red);
            box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.2);
        }

        .input-icon {
            background: var(--um-red);
            color: white;
            min-width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px 0 0 8px;
        }

        .btn-um {
            background: var(--um-yellow);
            color: var(--um-red);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-um:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.15);
        }

        .auth-links a {
            color: var(--um-red);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="container">
            <div class="login-card">
                <div class="login-header">
                    <img src="images/um-logo.png" alt="UM Logo">
                    <h3 class="mb-0">Collaboration Room Access</h3>
                </div>
                
                <div class="login-body">
                    <?php if($error): ?>
                    <div class="alert alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="authenticate.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">UM Email</label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" name="email" placeholder="name@umindanao.edu.ph" required>
                            </div>
                        </div>

    <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-icon">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password" class="form-control" name="password" required>
        </div>
    </div>

    <button type="submit" class="btn btn-um w-100 mb-3">
        <i class="fas fa-sign-in-alt me-2"></i>Sign In
    </button>
</form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>