<?php
session_start();
include 'db_connection.php'; // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // User's email (assuming it's passed in the form)
    $amount = floatval($_POST['amount']); // Purchase amount

    // Check if the user has enough balance
    $check_balance_sql = "SELECT balance FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_balance_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($balance);
    $stmt->fetch();
    $stmt->close();

    if ($balance >= $amount) {
        // Deduct amount
        $update_sql = "UPDATE users SET balance = balance - ? WHERE email = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ds", $amount, $email);
        if ($stmt->execute()) {
            echo "Purchase successful! New balance: " . ($balance - $amount);
        } else {
            echo "Error updating balance.";
        }
        $stmt->close();
    } else {
        echo "Insufficient balance!";
    }

    $conn->close();
}
?>