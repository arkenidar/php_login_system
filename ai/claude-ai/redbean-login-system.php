<?php
/**
 * RedBeanPHP Login System
 * 
 * A simple yet secure login system built with RedBeanPHP
 */

// Require Composer's autoloader
require 'vendor/autoload.php';

// Import RedBeanPHP
use RedBeanPHP\R as R;

// Start or resume session
session_start();

// Setup database connection
function setupDatabase() {
    // Configure the database connection (MySQL in this example)
    R::setup('mysql:host=localhost;dbname=login_system', 'username', 'password');
    
    // In development mode, this will create tables and columns as needed
    R::freeze(false);
    
    // For production, set to true to prevent database structure modifications
    // R::freeze(true);
}

// Initialize the database
setupDatabase();

/**
 * User Registration Function
 * 
 * @param string $username The username
 * @param string $email The email address
 * @param string $password The password (will be hashed)
 * @return array Success status and message
 */
function registerUser($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    // Check if username already exists
    $existingUser = R::findOne('user', 'username = ?', [$username]);
    if ($existingUser) {
        return ['success' => false, 'message' => 'Username already exists'];
    }
    
    // Check if email already exists
    $existingEmail = R::findOne('user', 'email = ?', [$email]);
    if ($existingEmail) {
        return ['success' => false, 'message' => 'Email already in use'];
    }
    
    // Create a new user bean
    $user = R::dispense('user');
    $user->username = $username;
    $user->email = $email;
    $user->password = password_hash($password, PASSWORD_DEFAULT);
    $user->created_at = date('Y-m-d H:i:s');
    
    // Store the user in the database
    $id = R::store($user);
    
    if ($id) {
        return ['success' => true, 'message' => 'Registration successful!'];
    } else {
        return ['success' => false, 'message' => 'Registration failed'];
    }
}

/**
 * User Login Function
 * 
 * @param string $username The username
 * @param string $password The password
 * @return array Success status and message
 */
function loginUser($username, $password) {
    // Validate input
    if (empty($username) || empty($password)) {
        return ['success' => false, 'message' => 'Username and password are required'];
    }
    
    // Find the user
    $user = R::findOne('user', 'username = ?', [$username]);
    
    if (!$user) {
        // User not found - but don't reveal this for security
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
    
    // Verify password
    if (password_verify($password, $user->password)) {
        // Password is correct, create session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        
        // Update last login timestamp
        $user->last_login = date('Y-m-d H:i:s');
        R::store($user);
        
        return ['success' => true, 'message' => 'Login successful!'];
    } else {
        // Password is incorrect
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
}

/**
 * Check if user is logged in
 * 
 * @return boolean True if logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Log out the current user
 */
function logoutUser() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
}

/**
 * Get current user data
 * 
 * @return object|null User bean or null if not logged in
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return R::load('user', $_SESSION['user_id']);
}

// Example of how to use these functions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submissions
    
    // Registration form
    if (isset($_POST['register'])) {
        $result = registerUser(
            $_POST['username'] ?? '',
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
        );
        
        if ($result['success']) {
            // Redirect or show success message
            header('Location: login.php?message=' . urlencode($result['message']));
            exit;
        } else {
            $error = $result['message'];
        }
    }
    
    // Login form
    if (isset($_POST['login'])) {
        $result = loginUser(
            $_POST['username'] ?? '',
            $_POST['password'] ?? ''
        );
        
        if ($result['success']) {
            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>