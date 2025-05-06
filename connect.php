<?php 
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "rentalCar";

// Creates Connection 
$conn = new mysqli($servername, $username, $password, $dbname);

// Tests Connection 
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>
