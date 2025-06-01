<?php
require_once 'includes/user.php';

$user = new User();
$user->logout();

header("Location: login.php");
exit();
?>
