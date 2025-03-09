<?php
session_start();
include 'db_connect.php'; // Ensure this file connects properly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "✅ Processing login...<br>";

    $username = $_POST['username'];
    $password = $_POST['password'];

    echo "Username: $username<br>";
    echo "Password: $password<br>";

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("❌ SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        echo "✅ User found!<br>";
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) { 
            echo "✅ Password verified! Redirecting...<br>";

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['wallet_amount'] = $user['balance'];

            header("Location: dashboard.php");
            exit();
        } else {
            echo "❌ Incorrect password!";
        }
    } else {
        echo "❌ User not found!";
    }
} else {
    echo "❌ Invalid request!";
}
?>