<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle cancellation
if (isset($_POST['cancel'])) {
    $booking_id = $_POST['booking_id'];

    // Set vehicle back to available
    $vehicle_stmt = $conn->prepare("SELECT vehicle_id FROM booking WHERE booking_id = ? AND user_id = ?");
    $vehicle_stmt->bind_param("ii", $booking_id, $user_id);
    $vehicle_stmt->execute();
    $vehicle_stmt->bind_result($vehicle_id);
    if ($vehicle_stmt->fetch()) {
        $vehicle_stmt->close();

        $conn->query("UPDATE vehicle SET status = 'available' WHERE vehicle_id = $vehicle_id");
        $conn->query("DELETE FROM booking WHERE booking_id = $booking_id AND user_id = $user_id");
        echo "<p style='color:green;'>Booking canceled successfully.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
</head>
<body>
    <h1>My Bookings</h1>
    <a href="dashboard.php">Back to Dashboard</a><br><br>

    <?php
    $stmt = $conn->prepare("SELECT b.booking_id, b.start_date, b.end_date, b.total_cost, v.brand, v.model 
                            FROM booking b JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                            WHERE b.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8">
            <tr><th>Vehicle</th><th>Start</th><th>End</th><th>Total</th><th>Action</th></tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['brand'] . " " . $row['model']); ?></td>
                    <td><?= $row['start_date']; ?></td>
                    <td><?= $row['end_date']; ?></td>
                    <td>$<?= $row['total_cost']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
                            <button type="submit" name="cancel" onclick="return confirm('Cancel this booking?')">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif;

    $stmt->close();
    ?>
</body>
</html>
