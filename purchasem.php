<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php'; // Ensure this file exists and connects to the database

// Check if user is logged in (modify this based on your login system)
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized Access - Please log in first.");
}

// Check if form data is sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input values
    $item_name = trim($_POST['item_name']);
    $amount = floatval($_POST['amount']);
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Validate input
    if (empty($item_name) || $amount <= 0) {
        die("Invalid input. Please enter valid item details.");
    }

    // Check user's balance
    $balance_check = $conn->prepare("SELECT balance FROM users WHERE id = ?");
    $balance_check->bind_param("i", $user_id);
    $balance_check->execute();
    $balance_check->bind_result($balance);
    $balance_check->fetch();
    $balance_check->close();

    if ($balance === null) {
        die("User not found.");
    }

    if ($balance < $amount) {
        die("Insufficient balance.");
    }

    // Deduct amount from balance
    $update_balance = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $update_balance->bind_param("di", $amount, $user_id);
    
    if ($update_balance->execute()) {
        // Record transaction
        $insert_transaction = $conn->prepare("INSERT INTO transactions (user_id, item_name, amount, transaction_date) VALUES (?, ?, ?, NOW())");
        $insert_transaction->bind_param("isd", $user_id, $item_name, $amount);
        $insert_transaction->execute();

        echo "Purchase successful! New balance: " . ($balance - $amount);
    } else {
        echo "Transaction failed.";
    }

    $update_balance->close();
    $insert_transaction->close();
} else {
    die("Unauthorized Access");
}

$conn->close();
?>