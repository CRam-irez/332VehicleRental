<?php
include 'connect.php';
$result = $conn->query("SELECT * FROM Vehicle");
while($row = $result->fetch_assoc()) {
    echo "<p>{$row['vehicle_id']} - {$row['brand']} {$row['model']} - {$row['status']}</p>";
}
?>
