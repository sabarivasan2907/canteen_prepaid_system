<?php
include('db.php'); // Database connection
require 'phpqrcode/qrlib.php'; // Include QR Code Library

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0) {
        echo "Email already registered!";
    } else {
        // Insert user into database
        $query = "INSERT INTO users (name, email, password, balance) VALUES ('$name', '$email', '$password', 0)";
        if(mysqli_query($conn, $query)) {
            $user_id = mysqli_insert_id($conn); // Get new user ID

            // Create QR Codes directory if not exists
            if (!file_exists('qr_codes')) {
                mkdir('qr_codes', 0777, true);
            }

            // Generate QR Code
            $qr_text = "ID: $user_id, Email: $email";
            $qr_file = "qr_codes/".$user_id.".png";
            QRcode::png($qr_text, $qr_file, QR_ECLEVEL_L, 4);

            // Save QR Code path in database
            mysqli_query($conn, "UPDATE users SET qr_code='$qr_file' WHERE id='$user_id'");

            // Redirect to dashboard after successful registration
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error in registration.";
        }
    }
}
?>