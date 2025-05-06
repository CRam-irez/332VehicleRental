<?php
include 'connect.php';
$result = $conn->query("SELECT * FROM Payment");
while($row = $result->fetch_assoc()) {
    echo "<p>Payment ID: {$row['payment_id']} - Booking: {$row['booking_id']} - Amount: {$row['amount']} - Status: {$row['payment_status']}</p>";
}
?>

