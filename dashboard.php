<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle booking form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate dates
    if (strtotime($end_date) < strtotime($start_date)) {
        echo "<p style='color:red;'>End date must be after start date.</p>";
    } else {
        // Calculate total cost
        $query = $conn->prepare("SELECT price_per_day FROM vehicle WHERE vehicle_id = ?");
        $query->bind_param("i", $vehicle_id);
        $query->execute();
        $query->bind_result($price);
        if ($query->fetch()) {
            $query->close();

            $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
            $days = $days < 1 ? 1 : $days; // Minimum 1 day charge
            $total_cost = $days * $price;

            // Insert booking
            $stmt = $conn->prepare("INSERT INTO booking (user_id, vehicle_id, start_date, end_date, total_cost) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iissd", $user_id, $vehicle_id, $start_date, $end_date, $total_cost);
            if ($stmt->execute()) {
                // Mark vehicle as unavailable
                $conn->query("UPDATE vehicle SET status = 'unavailable' WHERE vehicle_id = $vehicle_id");
                echo "<p style='color:green;'>Booking confirmed for $days day(s)! Total cost: $$total_cost</p>";
            } else {
                echo "<p style='color:red;'>Booking failed. Please try again.</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color:red;'>Could not retrieve vehicle price.</p>";
            $query->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['name']); ?>!</h1>
<a href="logout.php">Log Out</a> |
<a href="my_bookings.php">My Bookings</a> |
<a href="pay_booking.php">Make a Payment</a>

    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .vehicle-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .vehicle-card h3 { margin-top: 0; }
    </style>
</head>
<body>

    <h2>Available Vehicles</h2>

    <?php
    $result = $conn->query("SELECT * FROM vehicle WHERE status = 'available'");
    if ($result && $result->num_rows > 0):
        while ($car = $result->fetch_assoc()):
    ?>
    <div class="vehicle-card">
        <h3><?= htmlspecialchars($car['brand'] . " " . $car['model']); ?></h3>
        <p>Type: <?= htmlspecialchars($car['type']); ?></p>
        <p>Price per day: $<?= htmlspecialchars($car['price_per_day']); ?></p>
        <form method="POST">
            <input type="hidden" name="vehicle_id" value="<?= $car['vehicle_id']; ?>">
            Start Date: <input type="date" name="start_date" required>
            End Date: <input type="date" name="end_date" required>
            <button type="submit" name="book">Book Now</button>
        </form>
    </div>
    <?php endwhile;
    else:
        echo "<p>No vehicles available at the moment.</p>";
    endif;
    ?>
</body>

<h2>Your Completed Transactions</h2>
<?php
$paid = $conn->prepare("
    SELECT v.brand, v.model, b.start_date, b.end_date, b.total_cost, p.payment_method
    FROM payment p
    JOIN booking b ON p.booking_id = b.booking_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    WHERE b.user_id = ?
");
$paid->bind_param("i", $user_id);
$paid->execute();
$paidResult = $paid->get_result();

if ($paidResult->num_rows > 0): ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>Vehicle</th>
            <th>Date Booked</th>
            <th>Amount</th>
            <th>Method</th>
        </tr>
        <?php while ($row = $paidResult->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['brand'] . ' ' . $row['model']); ?></td>
            <td><?= $row['start_date'] . ' to ' . $row['end_date']; ?></td>
            <td>$<?= $row['total_cost']; ?></td>
            <td><?= $row['payment_method']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>You have no completed payments yet.</p>
<?php endif;
$paid->close();
?>
</html>
