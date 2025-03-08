<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pizza = $_POST['pizza'];
    $burger = $_POST['burger'];
    $milkshake = $_POST['milkshake'];
    $cake = $_POST['cake'];

    $prices = [
        "pizza" => 120,
        "burger" => 80,
        "milkshake" => 100,
        "cake" => 150
    ];

    $total = ($pizza * $prices["pizza"]) + ($burger * $prices["burger"]) +
             ($milkshake * $prices["milkshake"]) + ($cake * $prices["cake"]);

    $_SESSION["order_summary"] = [
        "Pizza" => $pizza,
        "Burger" => $burger,
        "Milkshake" => $milkshake,
        "Cake" => $cake,
        "Total" => $total
    ];

    header("Location: order_summary.php");
    exit();
}