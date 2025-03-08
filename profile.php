<?php
session_start();
include 'db_connect.php'; // Ensure this file connects to your database

// Fetch the logged-in user’s details
$user_id = $_SESSION['user_id']; // Assuming you store the user ID in session
$query = "SELECT name FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$username = $row['name']; 

// Define QR code path
$qr_code_path = "qrcodes/" . $username . ".png";
?>

<img src="<?php echo $qr_code_path; ?>" alt="User QR Code">