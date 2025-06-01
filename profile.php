<?php
require_once 'includes/security_headers.php';
require_once 'includes/user.php';
require_once 'includes/security.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$currentUser = $user->getCurrentUser();
$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // CSRF Protection
        if (!isset($_POST['csrf_token']) || !Security::validateCSRFToken($_POST['csrf_token'])) {
            throw new Exception("Invalid request");
        }

        if (isset($_POST['update_profile'])) {
            $name = Security::sanitizeInput($_POST['name'] ?? '');
            
            if (empty($name)) {
                throw new Exception("Name is required");
            }

            if ($user->updateProfile($name)) {
                $success_message = "Profile updated successfully!";
                $currentUser['name'] = $name; // Update display
            }
        }

        if (isset($_POST['change_password'])) {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                throw new Exception("All password fields are required");
            }

            if ($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match");
            }

            if (!Security::validatePassword($new_password)) {
                throw new Exception("Password must be at least 8 characters with uppercase, lowercase, and number");
            }

            if ($user->changePassword($current_password, $new_password)) {
                $success_message = "Password changed successfully!";
            }
        }

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

$csrf_token = Security::generateCSRFToken();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - Secure Portal</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-3">
        <header>
            <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                <?php include 'header.php'; ?>
            </div>

            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h1 class="display-4 fw-normal text-body-emphasis">Profile Settings</h1>
                <p class="fs-5 text-body-secondary">Manage your account information</p>
            </div>
        </header>
        
        <main>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Profile Update Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Update Profile</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?php echo htmlspecialchars($currentUser['name']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" 
                                           value="<?php echo htmlspecialchars($currentUser['email']); ?>" disabled>
                                    <div class="form-text">Email cannot be changed for security reasons</div>
                                </div>
                                
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    Update Profile
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Password Change Form -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Change Password</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" 
                                           name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" 
                                           name="new_password" required>
                                    <div class="form-text">
                                        Password must be at least 8 characters with uppercase, lowercase, and number
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" required>
                                </div>
                                
                                <button type="submit" name="change_password" class="btn btn-warning">
                                    Change Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <?php include 'footer.php'; ?>
    </footer>
    
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
