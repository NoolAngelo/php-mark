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
    <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="dashboard.php">Dashboard</a>
    <?php if ($currentUser['email'] === 'admin@example.com'): ?>
      <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="admin.php">Admin</a>
    <?php endif; ?>
    <div class="dropdown">
      <a class="me-3 py-2 link-body-emphasis text-decoration-none dropdown-toggle" 
         href="#" role="button" data-bs-toggle="dropdown">
        <?php echo htmlspecialchars($currentUser['name']); ?>
      </a>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
      </ul>
    </div>
  <?php else: ?>
    <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="registration.php">Register</a>
    <a class="me-3 py-2 link-body-emphasis text-decoration-none" href="login.php">Login</a>
  <?php endif; ?>
</nav>