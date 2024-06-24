<?php
$servername = "localhost"; // Change this to your server's address if it's not localhost
$username = "your_username"; // Change this to your phpMyAdmin username
$password = "your_password"; // Change this to your phpMyAdmin password
$dbname = "your_database"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
