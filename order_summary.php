<?php
session_start();
include 'db_connect.php'; 
if (!isset($_SESSION["order_summary"])) {
    header("Location: order.php");
    exit();
}

$order = $_SESSION["order_summary"];
$userId = $_SESSION['user_id'];

// Updating new wallet in session
$_SESSION['wallet_amount'] = $_SESSION['wallet_amount'] - $order['Total'];

// Updating wallet amount in database
$sql = "UPDATE users SET balance = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

// Ensure wallet_amount is numeric to prevent SQL injection
$walletAmount = (float)$_SESSION['wallet_amount']; 

$stmt->bind_param("ds", $walletAmount, $userId); 
$stmt->execute();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
    <div class="order-summary">
        <h2>Order Summary</h2>
        <ul>
            <?php foreach ($order as $item => $qty) {
                if ($item != "Total" && $qty > 0) {
                    echo "<li>$item: $qty</li>";
                }
            } ?>
        </ul>
        <h3>Total Amount: ₹<?php echo $order["Total"]; ?></h3>

        <p class="success-msg">✔ Your order has been placed successfully!</p>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>