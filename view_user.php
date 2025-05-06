<?php
include 'connect.php';
$result = $conn->query("SELECT * FROM Users");
while($row = $result->fetch_assoc()) {
    echo "<p>{$row['user_id']} - {$row['name']} - {$row['email']} - {$row['phone']}</p>";
}
?>
