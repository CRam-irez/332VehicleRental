<?php
session_start();
include 'connect.php'; // Make sure your DB connection is set up

// Fetch available cars
$sql = "SELECT * FROM Vehicle WHERE status = 'available' LIMIT 3";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Car Rental - Home</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .car { border: 1px solid #ccc; padding: 15px; margin: 10px 0; }
        .buttons { margin-top: 15px; }
    </style>
</head>
<body>
    <h1>Welcome to Wheels n Deals!</h1>
    <h2>You need the wheels n we got the deals</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Hello, <?php echo $_SESSION['name']; ?>! <a href="dashboard.php">Go to Dashboard</a> | <a href="logout.php">Log Out</a></p>
    <?php else: ?>
        <div class="buttons">
            <a href="login.html">Log In</a> | <a href="register.html">Sign Up</a>
        </div>
    <?php endif; ?>

    <h2>Browse Available Cars</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($car = $result->fetch_assoc()): ?>
            <div class="car">
                <strong><?php echo $car['brand'] . ' ' . $car['model']; ?></strong><br>
                Type: <?php echo $car['type']; ?><br>
                Price per day: $<?php echo $car['price_per_day']; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No cars available right now. Please check back soon!</p>
    <?php endif; ?>
</body>
</html>
