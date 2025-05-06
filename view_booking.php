<?php
include 'connect.php';
$result = $conn->query("SELECT * FROM Booking");
while($row = $result->fetch_assoc()) {
    echo "<p>Booking ID: {$row['booking_id']} - User: {$row['user_id']} - Vehicle: {$row['vehicle_id']} - {$row['start_date']} to {$row['end_date']} - Total: {$row['total_cost']}</p>";
}
?>