<?php
include 'connect.php';
$id = $_GET['vehicle_id'];
$sql = "DELETE FROM Vehicle WHERE vehicle_id = $id";
echo $conn->query($sql) ? "Vehicle deleted." : "Error: $conn->error";
?>