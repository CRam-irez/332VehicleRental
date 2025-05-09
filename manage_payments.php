<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = $conn->prepare("SELECT payment_id, method, card_number, paypal_email, status FROM payments WHERE user_id = ?");
$result->bind_param("i", $user_id);
$result->execute();
$res = $result->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Payments</title>
</head>
<body>
    <h1>Manage Your Payment Methods</h1>
    <a href="dashboard.php">Back to Dashboard</a>
    <a href="insert_payment.php">Add Payment Method</a>
    <hr>

    <?php if ($res->num_rows > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Method</th>
                <th>Details</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['method']) ?></td>
                    <td>
                        <?= $row['method'] === 'credit_card'
                            ? htmlspecialchars($row['card_number'])
                            : htmlspecialchars($row['paypal_email']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><a href="update_payment.php?id=<?= $row['payment_id'] ?>">Edit</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No payment methods found.</p>
    <?php endif; ?>
</body>
</html>
