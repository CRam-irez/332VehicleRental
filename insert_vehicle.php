<?php
session_start();
require 'connect.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Insert vehicle if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $price_per_day = $_POST['price_per_day'];
    $status = $_POST['status'];

    if (!empty($brand) && !empty($model) && !empty($price_per_day)) {
        $stmt = $conn->prepare("INSERT INTO vehicle (brand, model, type, price_per_day, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $brand, $model, $type, $price_per_day, $status);
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Vehicle added successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color: red;'>Please fill in all required fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Vehicle</title>
</head>
<body>
    <h2>Add a New Vehicle</h2>
    <form method="POST" action="">
        Brand: <input type="text" name="brand" required><br><br>
        Model: <input type="text" name="model" required><br><br>
        Type: <input type="text" name="type"><br><br>
        Price Per Day: <input type="number" step="0.01" name="price_per_day" required><br><br>
        Status:
        <select name="status">
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
            <option value="maintenance">Maintenance</option>
        </select><br><br>
        <input type="submit" value="Add Vehicle">
    </form>

    <hr>
    <h3>Current Vehicles</h3>
    <?php
    $result = $conn->query("SELECT * FROM vehicle");

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>
                <tr>
                    <th>ID</th><th>Brand</th><th>Model</th><th>Type</th><th>Price/Day</th><th>Status</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['vehicle_id']}</td>
                    <td>{$row['brand']}</td>
                    <td>{$row['model']}</td>
                    <td>{$row['type']}</td>
                    <td>\${$row['price_per_day']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No vehicles found.";
    }

    $conn->close();
    ?>
</body>
</html>
