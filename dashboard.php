<?php
// Start session
session_start();
include 'db_connect.php'; // Ensure this file contains database connection details

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get logged-in user ID from session
$user_id = $_SESSION['user_id']; // Now it dynamically gets the correct user ID

// Fetch user balance from session
$balance = $_SESSION['wallet_amount'] ?? 0;

// Fetch recent transactions 
$transactions = mysqli_query($conn, "SELECT * FROM transactions WHERE id = $user_id ORDER BY created_at DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Wait a Minute for <span>Delicious.</span></h1>
        <p>Quick, Delicious & Hassle-Free – Order Now!</p>
        <a href="order.php"class="Order-btn">order now</a>

        <div class="food-images">
            <img src="images/Pizza.png" alt="Pizza" class="food">
            <img src="images/Milkshake.png" alt="Milkshake" class="food">
            <img src="images/Burger.png" alt="Burger" class="food">
            <img src="images/Cake.png" alt="Cake" class="food">
        </div>

        <div class="options">
            <div class="box">Balance <br> ₹<?php echo number_format($balance, 2); ?></div>
            <div class="box">
                Transaction History <br>
                <?php while ($txn = mysqli_fetch_assoc($transactions)) {
                    echo "₹" . $txn['amount'] . " on " . date("d M Y", strtotime($txn['created_at'])) . "<br>";
                } ?>
            </div>
            <div class="box"><a href="deposit.php" style="color: white; text-decoration: none;">Deposit <br> Add money to your account</a></div>
            <div class="box"><a href="settings.php" style="color: white; text-decoration: none;">More Options <br> Settings & Help</a></div>
        </div>
    </div>
</body>
</html>