<?php
include 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $total = $_POST['total_cost'];
    $sql = "INSERT INTO Booking (user_id, vehicle_id, start_date, end_date, total_cost) VALUES ($user_id, $vehicle_id, '$start', '$end', $total)";
    echo $conn->query($sql) ? "Booking created." : "Error: $conn->error";
}
?>

<?php

