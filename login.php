<?php
session_start(); // Start session for user login tracking

require 'connect.php'; // DB connection

$email = $_POST['email'];
$password = $_POST['password'];

// Validate input
if (empty($email) || empty($password)) {
    die("Please fill in all fields.");
}

// Get user info including role
$stmt = $conn->prepare("SELECT user_id, name, password, role FROM Users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        // Save user info in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "No user found with that email.";
}

$stmt->close();
$conn->close();
?>
