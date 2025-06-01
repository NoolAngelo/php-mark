<?php
require_once 'includes/user.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$currentUser = $user->getCurrentUser();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome - Secure Portal</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-3">
        <header>
            <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                <?php include 'header.php'; ?>
            </div>

            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h1 class="display-4 fw-normal text-body-emphasis">Welcome, <?php echo htmlspecialchars($currentUser['name']); ?>!</h1>
                <p class="fs-5 text-body-secondary">Email: <?php echo htmlspecialchars($currentUser['email']); ?></p>
                <p class="text-muted">You are securely logged in.</p>
            </div>
        </header>
        
        <main>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h3>Dashboard</h3>
                        </div>
                        <div class="card-body">
                            <p>Welcome to your secure dashboard. Your session is protected with:</p>
                            <ul>
                                <li>CSRF Protection</li>
                                <li>Secure Password Hashing</li>
                                <li>SQL Injection Prevention</li>
                                <li>Session Security</li>
                                <li>Input Validation & Sanitization</li>
                            </ul>
                            
                            <div class="mt-4">
                                <a href="memberlist.php" class="btn btn-primary">View Members</a>
                                <a href="logout.php" class="btn btn-danger">Logout</a>
                            </div>
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