<?php
$host = "localhost";
$username = "root"; // Default XAMPP MySQL user
$password = ""; // Default is empty
$database = "canteen_prepaid"; // Replace with your database name

// Create a new connection using MySQLi Object-Oriented
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>