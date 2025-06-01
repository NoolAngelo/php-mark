<?php
require_once 'includes/security_headers.php';
require_once 'includes/user.php';
require_once 'includes/audit_logger.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Simple admin check - you can enhance this with role-based access
$currentUser = $user->getCurrentUser();
$isAdmin = ($currentUser['email'] === 'admin@example.com'); // Basic admin check

if (!$isAdmin) {
    header("Location: welcome.php");
    exit();
}

$auditLogger = new AuditLogger();

// Get all users for admin view
try {
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "SELECT id, name, email, created_at, last_login, failed_login_attempts FROM members ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $allUsers = $stmt->fetchAll();
    
    // Get recent login attempts
    $auditQuery = "SELECT la.*, m.name FROM login_audit la 
                   LEFT JOIN members m ON la.user_id = m.id 
                   ORDER BY la.login_time DESC LIMIT 20";
    $auditStmt = $conn->prepare($auditQuery);
    $auditStmt->execute();
    $recentAttempts = $auditStmt->fetchAll();
    
} catch (Exception $e) {
    $allUsers = [];
    $recentAttempts = [];
    error_log("Admin panel error: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Secure Portal</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/datatables.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid py-3">
        <header>
            <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                <?php include 'header.php'; ?>
            </div>

            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h1 class="display-4 fw-normal text-body-emphasis">
                    Admin Panel
                    <span class="badge bg-danger">Admin Access</span>
                </h1>
                <p class="fs-5 text-body-secondary">System administration and security monitoring</p>
            </div>
        </header>
        
        <main>
            <div class="row">
                <!-- Users Management -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>User Management</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="usersTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Created</th>
                                            <th>Last Login</th>
                                            <th>Failed Attempts</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allUsers as $userData): ?>
                                        <tr>
                                            <td><?php echo $userData['id']; ?></td>
                                            <td><?php echo htmlspecialchars($userData['name']); ?></td>
                                            <td><?php echo htmlspecialchars($userData['email']); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($userData['created_at'])); ?></td>
                                            <td>
                                                <?php if ($userData['last_login']): ?>
                                                    <?php echo date('M j, H:i', strtotime($userData['last_login'])); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Never</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $userData['failed_login_attempts'] > 3 ? 'danger' : 'secondary'; ?>">
                                                    <?php echo $userData['failed_login_attempts']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Monitoring -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Login Attempts</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            <?php foreach ($recentAttempts as $attempt): ?>
                                <div class="mb-2 p-2 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-<?php echo $attempt['login_status'] === 'success' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($attempt['login_status']); ?>
                                        </span>
                                        <small class="text-muted">
                                            <?php echo date('M j, H:i', strtotime($attempt['login_time'])); ?>
                                        </small>
                                    </div>
                                    <small class="text-muted d-block">
                                        <?php echo htmlspecialchars($attempt['email']); ?>
                                    </small>
                                    <small class="text-muted d-block">
                                        IP: <?php echo htmlspecialchars($attempt['ip_address']); ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- System Stats -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>System Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary"><?php echo count($allUsers); ?></h4>
                                    <small>Total Users</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success"><?php echo count(array_filter($recentAttempts, function($a) { return $a['login_status'] === 'success'; })); ?></h4>
                                    <small>Recent Logins</small>
                                </div>
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
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "pageLength": 10,
                "order": [[ 0, "desc" ]]
            });
        });
    </script>
</body>
</html>
