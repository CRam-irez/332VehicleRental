<?php
include 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['vehicle_id'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $sql = "UPDATE Vehicle SET brand='$brand', model='$model', type='$type', price_per_day=$price, status='$status' WHERE vehicle_id=$id";
    echo $conn->query($sql) ? "Vehicle updated." : "Error: $conn->error";
}
?>