<?php
// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost"; // Change if needed
$user = "root"; // Your MySQL username
$pass = ""; // Your MySQL password (leave empty if not set)
$dbname = "canteen_prepaid"; // Your database name

try {
    // Create database connection
    $conn = new mysqli($host, $user, $pass, $dbname);

    // Set character encoding to UTF-8
    $conn->set_charset("utf8");

    // Debugging (Optional, for testing)
    // echo "✔ Database connection successful";
} catch (Exception $e) {
    exit("❌ Database Connection Failed: " . $e->getMessage());
}
?>