<?php
session_start();
include 'db_connect.php';
require 'phpqrcode/qrlib.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT id, name, email, balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found");
}

// QR Code content
$qr_content = json_encode([
    'User ID' => $user['id'],
    'Name' => $user['name'],
    'Email' => $user['email'],
    'Balance' => $user['balance']
]);

// Set QR code filename
$qr_filename = "qrcodes/user_" . $user['id'] . ".png";

// Generate QR code
QRcode::png($qr_content, $qr_filename, QR_ECLEVEL_L, 5);

// Save file path in database
$update_sql = "UPDATE users SET qr_code = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("si", $qr_filename, $user['id']);
$update_stmt->execute();

echo "QR Code generated successfully: <br>";
echo "<img src='$qr_filename' />";
?>