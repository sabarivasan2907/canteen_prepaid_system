<?php
include 'db_connect.php';
require 'phpqrcode/qrlib.php';

// Fetch all users
$sql = "SELECT id, name, email, balance FROM users";
$result = $conn->query($sql);

while ($user = $result->fetch_assoc()) {
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
}

echo "All QR codes generated successfully!";
?>