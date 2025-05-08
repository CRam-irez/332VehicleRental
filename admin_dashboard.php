<?php
session_start();

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .admin-section { border: 1px solid #ccc; padding: 20px; }
    </style>
</head>
<body>
    <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
    <a href="logout.php">Log Out</a>

    <div class="admin-section">
        <h2>Admin Tools</h2>
        <ul>
            <li><a href="insert_vehicle.php">Add New Vehicle</a></li>
            <li><a href="manage_vehicles.php">Edit/Delete Vehicles</a></li>
            <li><a href="view_users.php">View Users</a></li>
        </ul>
    </div>
</body>
</html>
