<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$id = $_GET['id'];
$order = $conn->query("SELECT * FROM orders WHERE id = $id")->fetch_assoc();
$items = $conn->query("SELECT oi.*, c.cake_name FROM order_items oi JOIN cakes c ON oi.cake_id = c.id WHERE oi.order_id = $id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Ruhara Cakes</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="dashboard.php" class="logo">🍰 Ruhara Cakes Admin</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div style="padding: 100px 5% 2rem;">
        <div class="glass-container fade-in" style="padding: 2.5rem; max-width: 800px; margin: 0 auto;">
            <h2 class="text-wow" style="margin-bottom: 1.5rem;">Order Details #<?php echo $order['id']; ?></h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h4 style="margin-bottom: 0.5rem;">Customer Information</h4>
                    <p><b>Name:</b> <?php echo $order['customer_name']; ?></p>
                    <p><b>Phone:</b> <?php echo $order['phone']; ?></p>
                    <p><b>Address:</b> <?php echo nl2br($order['address']); ?></p>
                </div>
                <div>
                    <h4 style="margin-bottom: 0.5rem;">Order Information</h4>
                    <p><b>Date:</b> <?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
                    <p><b>Status:</b> <?php echo ucfirst($order['status']); ?></p>
                    <p><b>Grand Total:</b> $<?php echo number_format($order['total'], 2); ?></p>
                </div>
            </div>

            <h4 style="margin-bottom: 1rem;">Items Ordered</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                        <th style="padding: 10px;">Cake Name</th>
                        <th style="padding: 10px;">Quantity</th>
                        <th style="padding: 10px;">Price</th>
                        <th style="padding: 10px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = $items->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 10px;"><?php echo $item['cake_name']; ?></td>
                        <td style="padding: 10px;"><?php echo $item['quantity']; ?></td>
                        <td style="padding: 10px;">$<?php echo number_format($item['price'], 2); ?></td>
                        <td style="padding: 10px;">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div style="margin-top: 2rem; text-align: right;">
                <a href="orders.php" class="btn btn-primary">Back to Orders</a>
            </div>
        </div>
    </div>
</body>
</html>
