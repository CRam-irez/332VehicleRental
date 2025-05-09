<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $method = $_POST['method'];
    $card_number = $_POST['card_number'] ?? null;
    $paypal_email = $_POST['paypal_email'] ?? null;

    $stmt = $conn->prepare("INSERT INTO payments (user_id, method, card_number, paypal_email, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt->bind_param("isss", $_SESSION['user_id'], $method, $card_number, $paypal_email);
    $stmt->execute();
    header("Location: manage_payments.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Payment Method</title>
</head>
<body>
    <h1>Add a Payment Method</h1>
    <a href="manage_payments.php">Back</a>

    <form method="POST">
        <label>Payment Method:</label>
        <select name="method" onchange="toggleFields(this.value)">
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
        </select><br><br>

        <div id="card_fields">
            <label>Card Number:</label>
            <input type="text" name="card_number"><br><br>
        </div>

        <div id="paypal_fields" style="display:none;">
            <label>PayPal Email:</label>
            <input type="email" name="paypal_email"><br><br>
        </div>

        <button type="submit">Add Payment</button>
    </form>

    <script>
        function toggleFields(value) {
            document.getElementById('card_fields').style.display = value === 'credit_card' ? 'block' : 'none';
            document.getElementById('paypal_fields').style.display = value === 'paypal' ? 'block' : 'none';
        }
    </script>
</body>
</html>
