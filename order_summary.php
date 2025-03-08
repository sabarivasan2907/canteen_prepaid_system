<?php
session_start();
if (!isset($_SESSION["order_summary"])) {
    header("Location: order.php");
    exit();
}

$order = $_SESSION["order_summary"];
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