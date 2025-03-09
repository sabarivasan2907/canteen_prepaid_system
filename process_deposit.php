<?php
// Enable error reporting for debugging (Disable in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Include database connection
require 'db_connect.php'; // Use require to ensure it loads

// Start session (if needed)
session_start();

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and validate input
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);

    if (!$user_id || !$amount || $user_id <= 0 || $amount <= 0) {
        header("Location: test_deposit.html?error=Invalid+User+ID+or+Amount");
        exit();
    }

    try {
        // Begin transaction
        $conn->begin_transaction();

        // Check if user exists and fetch balance
        $checkUser = $conn->prepare("SELECT balance FROM users WHERE user_id = ?");
        $checkUser->bind_param("i", $user_id);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows === 0) {
            $conn->rollback();
            header("Location: test_deposit.html?error=User+ID+not+found");
            exit();
        }

        // Fetch current balance
        $row = $result->fetch_assoc();
        $current_balance = $row['balance'];
        $checkUser->close();

        // Calculate new balance
        $new_balance = $current_balance + $amount;

        
        // Update balance
        $stmt = $conn->prepare("UPDATE users SET balance = ? WHERE user_id = ?");
        $stmt->bind_param("di", $new_balance, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $conn->commit(); // Commit transaction

            // Update wallet amount in session
            $_SESSION['wallet_amount'] = $new_balance;
        
            header("Location: test_deposit.html?success=1&balance=" . $new_balance);
            exit();
        } else {
            $conn->rollback(); // Rollback in case of failure
            header("Location: test_deposit.html?error=Deposit+failed");
            exit();
        }

    } catch (Exception $e) {
        $conn->rollback(); // Ensure rollback on error
        error_log("Deposit Error: " . $e->getMessage()); // Log error for debugging
        header("Location: test_deposit.html?error=Something+went+wrong");
        exit();
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    header("Location: test_deposit.html?error=Invalid+Request");
    exit();
}
?>