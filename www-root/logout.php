<?php
// Include the login system file
require_once '../php-includes/login_system.php';

// Log out the user
logoutUser();

// Redirect to login page
header('Location: login.php?message=' . urlencode('You have been logged out successfully'));
exit;