<?php
session_start();
include 'db_connect.php'; // Ensure this file correctly connects to your database

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. Please log in.");
}

$user_id = $_SESSION['user_id'];

// Validate and sanitize input
if (!isset($_POST['amount'], $_POST['item'], $_POST['type']) || empty($_POST['amount']) || empty($_POST['item'])) {
    die("Missing transaction details.");
}

$amount = floatval($_POST['amount']);
$item = htmlspecialchars($_POST['item']);
$type = htmlspecialchars($_POST['type']);
$transaction_date = date("Y-m-d H:i:s");

// Validate amount
if ($amount <= 0) {
    die("Invalid amount. Must be greater than zero.");
}

$conn->begin_transaction();

try {
    // Fetch user balance
    $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        throw new Exception("User not found.");
    }

    $balance = $user['balance'];

    // Process transaction based on type
    if ($type == "Purchase") {
        if ($balance < $amount) {
            throw new Exception("Insufficient balance.");
        }
        $new_balance = $balance - $amount;
    } elseif ($type == "Deposit") {
        $new_balance = $balance + $amount;
    } else {
        throw new Exception("Invalid transaction type.");
    }

    // Update user balance
    $stmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->bind_param("di", $new_balance, $user_id);
    $stmt->execute();

    // Insert transaction record
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, item, amount, type, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $item, $amount, $type, $transaction_date);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo "Transaction successful!";
    header("Location: dashboard.php");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    die("Transaction failed: " . $e->getMessage());
}
?>