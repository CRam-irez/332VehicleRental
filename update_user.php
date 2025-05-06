<?php
include 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $sql = "UPDATE Users SET name='$name', email='$email', phone='$phone' WHERE user_id=$id";
    echo $conn->query($sql) ? "User updated." : "Error: $conn->error";
}
?>

