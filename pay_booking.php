<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle payment form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pay'])) {
    $booking_id = $_POST['booking_id'];
    $payment_method = $_POST['payment_method'];

    // Fetch the total amount for this booking
    $stmt = $conn->prepare("SELECT total_cost FROM booking WHERE booking_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($amount);
    
    if ($stmt->fetch()) {
        $stmt->close();

        // Insert into payment table
        $insert = $conn->prepare("INSERT INTO payment (booking_id, payment_method, amount, payment_status) VALUES (?, ?, ?, 'completed')");
        $insert->bind_param("isd", $booking_id, $payment_method, $amount);
        if ($insert->execute()) {
            $message = "✅ Payment successful using $payment_method!";
        } else {
            $message = "❌ Payment failed. Please try again.";
        }
        $insert->close();
    } else {
        $message = "❌ Invalid booking or you do not own this booking.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make a Payment</title>
</head>
<body>
    <h1>Make a Payment</h1>
    <a href="dashboard.php">Back to Dashboard</a>

    <?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

    <h2>Unpaid Bookings</h2>
    <?php
    // Show bookings that haven't been paid for yet
    $result = $conn->query("
        SELECT b.booking_id, v.brand, v.model, b.total_cost
        FROM booking b
        JOIN vehicle v ON b.vehicle_id = v.vehicle_id
        LEFT JOIN payment p ON b.booking_id = p.booking_id
        WHERE b.user_id = $user_id AND p.booking_id IS NULL
    ");

    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
    <form method="POST" style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
        <p><strong><?= htmlspecialchars($row['brand'] . ' ' . $row['model']); ?></strong></p>
        <p>Booking ID: <?= $row['booking_id']; ?> | Amount Due: $<?= $row['total_cost']; ?></p>
        <label>
            Payment Method:
            <select name="payment_method" required>
                <option value="">Select</option>
                <option value="Card">Card</option>
                <option value="PayPal">PayPal</option>
            </select>
        </label>
        <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
        <button type="submit" name="pay">Pay Now</button>
    </form>
    <?php endwhile;
    else:
        echo "<p>You have no unpaid bookings.</p>";
    endif;
    ?>
</body>
</html>
