<?php
session_start();
include 'config.php'; // Ensure database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch transactions for the logged-in user
$stmt = $conn->prepare("SELECT amount, type, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        .transaction-history-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .back-btn {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="transaction-history-container">
        <h2>Transaction History</h2>
        <table>
            <thead>
                <tr>
                    <th>Amount (₹)</th>
                    <th>Type</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo number_format($row['amount'], 2); ?></td>
                            <td><?php echo ucfirst($row['type']); ?></td>
                            <td><?php echo date("d-M-Y h:i A", strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center;">No transactions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>