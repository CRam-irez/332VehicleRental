<?php
session_start();
require 'connect.php';

// Admin access only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Update status
if (isset($_POST['update_status'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE vehicle SET status = ? WHERE vehicle_id = ?");
    $stmt->bind_param("si", $status, $vehicle_id);
    $stmt->execute();
    $stmt->close();
}

// Delete vehicle
if (isset($_POST['delete'])) {
    $vehicle_id = $_POST['vehicle_id'];

    $stmt = $conn->prepare("DELETE FROM vehicle WHERE vehicle_id = ?");
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Vehicles</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: center; }
    </style>
</head>
<body>
    <h1>Manage Vehicles</h1>
    <a href="admin_dashboard.php">Back to Dashboard</a><br><br>

    <?php
    $result = $conn->query("SELECT * FROM vehicle");
    if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th><th>Brand</th><th>Model</th><th>Type</th><th>Price</th><th>Status</th><th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="vehicle_id" value="<?= $row['vehicle_id']; ?>">
                        <td><?= $row['vehicle_id']; ?></td>
                        <td><?= $row['brand']; ?></td>
                        <td><?= $row['model']; ?></td>
                        <td><?= $row['type']; ?></td>
                        <td>$<?= $row['price_per_day']; ?></td>
                        <td>
                            <select name="status">
                                <option value="available" <?= $row['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                                <option value="unavailable" <?= $row['status'] === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                                <option value="maintenance" <?= $row['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" name="update_status">Update</button>
                            <button type="submit" name="delete" onclick="return confirm('Are you sure?')">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No vehicles found.</p>
    <?php endif; ?>
</body>
</html>
