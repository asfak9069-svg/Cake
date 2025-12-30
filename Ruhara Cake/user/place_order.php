<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION['cart'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $total = $_POST['total'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into orders
        $sql_order = "INSERT INTO orders (customer_name, phone, address, total, user_id) VALUES ('$name', '$phone', '$address', '$total', $user_id)";
        $conn->query($sql_order);
        $order_id = $conn->insert_id;

        // Insert into order_items
        foreach ($_SESSION['cart'] as $cake_id => $qty) {
            $cake = $conn->query("SELECT price FROM cakes WHERE id = $cake_id")->fetch_assoc();
            $price = $cake['price'];
            $sql_items = "INSERT INTO order_items (order_id, cake_id, quantity, price) VALUES ($order_id, $cake_id, $qty, $price)";
            $conn->query($sql_items);
        }

        $conn->commit();
        unset($_SESSION['cart']); // Clear cart
        
        // Success Page
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Order Success - Ruhara Cakes</title>
            <link rel='stylesheet' href='../assets/css/style.css'>
        </head>
        <body style='display: flex; align-items: center; justify-content: center; height: 100vh; text-align: center;'>
            <div class='glass-container' style='padding: 3rem;'>
                <h1 style='color: white; font-size: 3rem; margin-bottom: 1rem;'>🍰 Order Placed!</h1>
                <p style='color: white; font-size: 1.2rem; margin-bottom: 2rem;'>Thank you, $name. Your order #$order_id has been received.</p>
                <a href='../index.php' class='btn btn-primary'>Back to Home</a>
            </div>
        </body>
        </html>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error placing order: " . $e->getMessage();
    }
} else {
    header("Location: menu.php");
}
?>
