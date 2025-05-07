<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'connect.php';
$user_id = $_SESSION['user_id'];

// You can expand this to show bookings, etc.
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; }
    </style>
</head>
<body>
    <h1>Welcome to your Dashboard, <?php echo $_SESSION['name']; ?>!</h1>
    <p><a href="logout.php">Log Out</a></p>

    <h2>Your Account</h2>
    <p>Email: <?php echo $_SESSION['email']; ?></p>

    <!-- Future: List bookings, support tickets, etc. -->
</body>
</html>
