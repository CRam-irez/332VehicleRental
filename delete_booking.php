<?php
include 'connect.php';
$id = $_GET['booking_id'];
$sql = "DELETE FROM Booking WHERE booking_id = $id";
echo $conn->query($sql) ? "Booking deleted." : "Error: $conn->error";
?>