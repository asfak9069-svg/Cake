<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cake_id = $_POST['cake_id'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if cake already in cart
    if (isset($_SESSION['cart'][$cake_id])) {
        $_SESSION['cart'][$cake_id] += $quantity;
    } else {
        $_SESSION['cart'][$cake_id] = $quantity;
    }

    header("Location: cart.php");
    exit();
}
?>
