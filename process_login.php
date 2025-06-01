<?php
require_once 'includes/security_headers.php';
require_once 'includes/user.php';
require_once 'includes/security.php';
require_once 'includes/rate_limiter.php';

session_start();

$error_message = '';
$success_message = '';

// Rate limiting
$rateLimiter = new RateLimiter();
$clientIP = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Check rate limiting
        if (!$rateLimiter->isAllowed($clientIP)) {
            $remainingTime = $rateLimiter->getRemainingTime($clientIP);
            throw new Exception("Too many login attempts. Please try again in " . ceil($remainingTime / 60) . " minutes.");
        }

        // CSRF Protection
        if (!isset($_POST['csrf_token']) || !Security::validateCSRFToken($_POST['csrf_token'])) {
            throw new Exception("Invalid request");
        }

        // Sanitize input
        $email = Security::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            throw new Exception("All fields are required");
        }

        $user = new User();
        
        if ($user->login($email, $password)) {
            header("Location: welcome.php");
            exit();
        }
        
    } catch (Exception $e) {
        // Record failed attempt for rate limiting
        $rateLimiter->recordAttempt($clientIP);
        
        $error_message = $e->getMessage();
        error_log("Login attempt failed: " . $e->getMessage() . " - IP: " . $clientIP);
    }
}

// If there's an error, redirect back to login with error
if (!empty($error_message)) {
    $_SESSION['login_error'] = $error_message;
    header("Location: login.php");
    exit();
}
?>
