<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
    <div class="order-container">
        <h2>Place Your Order</h2>
        <form action="order_process.php" method="post">
            <label>Pizza (₹120):</label>
            <input type="number" name="pizza" min="0" value="0"><br>

            <label>Burger (₹80):</label>
            <input type="number" name="burger" min="0" value="0"><br>

            <label>Milkshake (₹100):</label>
            <input type="number" name="milkshake" min="0" value="0"><br>

            <label>Cake (₹150):</label>
            <input type="number" name="cake" min="0" value="0"><br>

            <button type="submit">Place Order</button>
        </form>

        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>