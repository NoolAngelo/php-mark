<?php
// Check if user is logged in for dynamic navigation
require_once 'includes/user.php';
$user = new User();
$isLoggedIn = $user->isLoggedIn();
$currentUser = $isLoggedIn ? $user->getCurrentUser() : null;
?>

<a href="index.php" class="d-flex align-items-center link-body-emphasis text-decoration-none">
  <img src="img/pic edited 400x400.png" width="40" height="32" class="me-2" alt="NoolAngelo">
  <span class="fs-4">NoolAngelo</span>
</a>

<nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
  <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="index.php">Homepage</a>
  
  <?php if ($isLoggedIn): ?>
    <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="memberlist.php">Member list</a>
    <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="welcome.php">Dashboard</a>
    <span class="me-3 py-2 text-muted">Welcome, <?php echo htmlspecialchars($currentUser['name']); ?></span>
    <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="logout.php">Logout</a>
  <?php else: ?>
    <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="registration.php">Register</a>
    <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="login.php">Login</a>
  <?php endif; ?>
</nav>