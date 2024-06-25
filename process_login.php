<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Password hashing is recommended for real applications
    $sql = "SELECT * FROM members WHERE email='$email' AND password='$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Successful login
        $_SESSION['email'] = $email;
        header("Location: welcome.php");
    } else {
        // Invalid credentials
        echo "Invalid email or password";
    }

    $conn->close();
}
