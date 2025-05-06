<?php
include 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $sql = "INSERT INTO Vehicle (brand, model, type, price_per_day, status) VALUES ('$brand', '$model', '$type', '$price', '$status')";
    echo $conn->query($sql) ? "Vehicle added successfully." : "Error: $conn->error";
}
?>

