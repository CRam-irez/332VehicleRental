<?php
include 'connect.php';
$id = $_GET['user_id'];
$sql = "DELETE FROM Users WHERE user_id = $id";
echo $conn->query($sql) ? "Users deleted." : "Error: $conn->error";
?>

