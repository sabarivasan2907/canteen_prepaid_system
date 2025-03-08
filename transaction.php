<?php
session_start();
include 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user balance
$query = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($balance);
$query->fetch();
$query->close();

// Fetch latest 5 transactions
$query = $conn->prepare("SELECT item, amount, type, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($item, $amount, $type, $created_at);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <h1>Welcome to Your Dashboard</h1>
    
    <div class="account-info">
        <h2>Your Balance: ₹<?php echo number_format($balance, 2); ?></h2>
    </div>

    <div class="transaction-history">
        <h3>Recent Transactions</h3>
        <table>
            <tr>
                <th>Item</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Date</th>
            </tr>
            <?php while ($query->fetch()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($item); ?></td>
                <td>₹<?php echo number_format($amount, 2); ?></td>
                <td><?php echo ucfirst($type); ?></td>
                <td><?php echo date("d M Y, h:i A", strtotime($created_at)); ?></td>
            </tr>
            <?php } ?>
        </table>
        <br>
        <a href="transaction.php" class="view-history-btn">View Full History</a>
    </div>

    <div class="dashboard-links">
        <a href="purchase.php">Make a Purchase</a>
        <a href="deposit.php">Deposit Money</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>

<?php
$query->close();
?>