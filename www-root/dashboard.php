<?php
// Include the login system file
require_once '../php-includes/login_system.php';

// Check if user is logged in, redirect to login page if not
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get current user data
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RedBean Login System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">RedBean Login System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Welcome, <?php echo htmlspecialchars($user->username); ?>!</h3>
                    </div>
                    <div class="card-body">
                        <p>You are successfully logged in.</p>
                        <p>Your email: <?php echo htmlspecialchars($user->email); ?></p>
                        <p>Account created: <?php echo htmlspecialchars($user->created_at); ?></p>
                        <p>Last login: <?php echo htmlspecialchars($user->last_login); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>