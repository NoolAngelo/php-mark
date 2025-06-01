<?php
require_once 'includes/user.php';
require_once 'includes/security.php';

// Start session with secure settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
session_start();

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // CSRF Protection
        if (!isset($_POST['csrf_token']) || !Security::validateCSRFToken($_POST['csrf_token'])) {
            throw new Exception("Invalid request");
        }

        // Sanitize input
        $name = Security::sanitizeInput($_POST['name'] ?? '');
        $email = Security::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            throw new Exception("All fields are required");
        }

        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match");
        }

        $user = new User();
        
        if ($user->register($name, $email, $password)) {
            $_SESSION['registration_success'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit();
        }
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        error_log("Registration attempt failed: " . $e->getMessage() . " - IP: " . $_SERVER['REMOTE_ADDR']);
    }
}

// If there's an error, redirect back to registration with error
if (!empty($error_message)) {
    $_SESSION['registration_error'] = $error_message;
    header("Location: registration.php");
    exit();
}
?>
