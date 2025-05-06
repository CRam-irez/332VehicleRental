<?php
include 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['booking_id'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $total = $_POST['total_cost'];
    $sql = "UPDATE Booking SET start_date='$start', end_date='$end', total_cost=$total WHERE booking_id=$id";
    echo $conn->query($sql) ? "Booking updated." : "Error: $conn->error";
}
?>
