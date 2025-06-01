<?php
require_once 'includes/security_headers.php';
require_once 'includes/user.php';
require_once 'includes/audit_logger.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$currentUser = $user->getCurrentUser();
$auditLogger = new AuditLogger();
$loginHistory = $auditLogger->getLoginHistory($currentUser['id'], 5);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Secure Portal</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .security-badge { font-size: 0.75rem; }
        .login-history { font-size: 0.9rem; }
    </style>
</head>

<body>
    <div class="container py-3">
        <header>
            <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                <?php include 'header.php'; ?>
            </div>

            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h1 class="display-4 fw-normal text-body-emphasis">
                    Welcome, <?php echo htmlspecialchars($currentUser['name']); ?>!
                    <span class="badge bg-success security-badge">Secure Session</span>
                </h1>
                <p class="fs-5 text-body-secondary">Email: <?php echo htmlspecialchars($currentUser['email']); ?></p>
            </div>
        </header>
        
        <main>
            <div class="row">
                <!-- User Info Card -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3>Dashboard</h3>
                            <span class="badge bg-primary">User ID: <?php echo $currentUser['id']; ?></span>
                        </div>
                        <div class="card-body">
                            <h5>Security Status</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>🔒 CSRF Protection</span>
                                            <span class="badge bg-success">Active</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>🛡️ Password Security</span>
                                            <span class="badge bg-success">Encrypted</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>🚫 SQL Injection</span>
                                            <span class="badge bg-success">Protected</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>⚡ Rate Limiting</span>
                                            <span class="badge bg-success">Enabled</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>🔐 Session Security</span>
                                            <span class="badge bg-success">Secure</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>🔍 Input Validation</span>
                                            <span class="badge bg-success">Active</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="memberlist.php" class="btn btn-primary me-2">View Members</a>
                                <a href="profile.php" class="btn btn-outline-secondary me-2">Edit Profile</a>
                                <a href="logout.php" class="btn btn-danger">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login History Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Login Activity</h5>
                        </div>
                        <div class="card-body login-history">
                            <?php if (!empty($loginHistory)): ?>
                                <?php foreach ($loginHistory as $login): ?>
                                    <div class="mb-2 p-2 border-bottom">
                                        <div class="d-flex justify-content-between">
                                            <span class="badge bg-<?php echo $login['login_status'] === 'success' ? 'success' : 'danger'; ?>">
                                                <?php echo ucfirst($login['login_status']); ?>
                                            </span>
                                            <small class="text-muted">
                                                <?php echo date('M j, H:i', strtotime($login['login_time'])); ?>
                                            </small>
                                        </div>
                                        <small class="text-muted d-block">
                                            IP: <?php echo htmlspecialchars($login['ip_address']); ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No recent activity</p>
                            <?php endif; ?>
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
