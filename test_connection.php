<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "canteen_prepaid";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
} else {
    echo "Database Connection Successful";
}
?>