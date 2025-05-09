<?php
session_start();
require 'connect.php';

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View All Bookings</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>All Bookings Overview</h1>
    <a href="admin_dashboard.php">← Back to Dashboard</a>

    <?php
    $query = "
        SELECT 
            u.name AS user_name,
            v.brand,
            v.model,
            b.start_date,
            b.end_date,
            IF(p.payment_id IS NULL, 'Not Paid', 'Paid') AS payment_status
        FROM booking b
        JOIN users u ON b.user_id = u.user_id
        JOIN vehicle v ON b.vehicle_id = v.vehicle_id
        LEFT JOIN payment p ON b.booking_id = p.booking_id
        ORDER BY b.start_date DESC
    ";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>User</th>
                <th>Vehicle</th>
                <th>Booking Start</th>
                <th>Booking End</th>
                <th>Payment Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_name']); ?></td>
                    <td><?= htmlspecialchars($row['brand'] . ' ' . $row['model']); ?></td>
                    <td><?= htmlspecialchars($row['start_date']); ?></td>
                    <td><?= htmlspecialchars($row['end_date']); ?></td>
                    <td><?= $row['payment_status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</body>
</html>
