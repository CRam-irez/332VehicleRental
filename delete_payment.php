<?php
include 'connect.php';
$id = $_GET['payment_id'];
$sql = "DELETE FROM Payment WHERE payment_id = $id";
echo $conn->query($sql) ? "Payment deleted." : "Error: $conn->error";
?>