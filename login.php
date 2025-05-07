<?php
session_start(); // Start session for user login tracking

// Include DB connection
require 'connect.php'; // Assumes your DB connection is in connect.php

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Validate input
if (empty($email) || empty($password)) {
    die("Please fill in all fields.");
}

// Prepare SQL to prevent SQL injection
$stmt = $conn->prepare("SELECT user_id, name, password FROM Users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify hashed password
    if (password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];

        echo "Login successful! Welcome, " . htmlspecialchars($user['name']) . ".";
        // You can also redirect:
        // header("Location: dashboard.php");
        // exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "No user found with that email.";
}

$stmt->close();
$conn->close();
?>
