<?php 
$servername = "localhost";
$username = "your_username"; 
$password = "your_password";
$dbname = "rental";

// Creates Connection 
$conn = new mysqli($servername, $username, $password, $dbname);

// Tests Connection 
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>
