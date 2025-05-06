<?php
include 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['payment_id'];
    $method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $status = $_POST['payment_status'];
    $sql = "UPDATE Payment SET payment_method='$method', amount=$amount, payment_status='$status' WHERE payment_id=$id";
    echo $conn->query($sql) ? "Payment updated." : "Error: $conn->error";
}
?>

