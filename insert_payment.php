<?php
include 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $status = $_POST['payment_status'];
    $sql = "INSERT INTO Payment (booking_id, payment_method, amount, payment_status) VALUES ($booking_id, '$method', $amount, '$status')";
    echo $conn->query($sql) ? "Payment recorded." : "Error: $conn->error";
}
?>

