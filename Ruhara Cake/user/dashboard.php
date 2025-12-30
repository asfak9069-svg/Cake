<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_res = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_res->fetch_assoc();

// Fetch orders
$orders_res = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Ruhara Cakes</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="../index.php" class="logo">🍰 Ruhara Cakes</a>
        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../index.php#menu">Menu</a>
            <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
            <a href="dashboard.php">My Account</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div style="padding: 120px 5% 5rem;">
        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem;">
            <!-- Profile Sidebar -->
            <div class="glass-container fade-in" style="padding: 2rem; height: fit-content;">
                <h3 class="text-wow" style="margin-bottom: 1.5rem;">My Profile</h3>
                <p style="margin-bottom: 0.5rem;"><b>Username:</b> <?php echo $user['username']; ?></p>
                <p style="margin-bottom: 0.5rem;"><b>Phone:</b> <?php echo $user['phone']; ?></p>
                <p style="margin-bottom: 1rem;"><b>Address:</b><br><?php echo nl2br($user['address']); ?></p>
                <a href="../index.php" class="btn btn-primary" style="width: 100%; text-align: center;">Continue Shopping</a>
            </div>

            <!-- Order History -->
            <div class="glass-container fade-in" style="padding: 2rem;">
                <h3 class="text-wow" style="margin-bottom: 1.5rem;">Order History</h3>
                
                <?php if ($orders_res->num_rows > 0): ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                                <th style="padding: 15px;">Order ID</th>
                                <th style="padding: 15px;">Date</th>
                                <th style="padding: 15px;">Total</th>
                                <th style="padding: 15px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $orders_res->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td style="padding: 15px;">#<?php echo $row['id']; ?></td>
                                <td style="padding: 15px;"><?php echo date('M d, Y', strtotime($row['order_date'])); ?></td>
                                <td style="padding: 15px; font-weight: 700;">$<?php echo number_format($row['total'], 2); ?></td>
                                <td style="padding: 15px;">
                                    <span style="padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; 
                                        background: <?php 
                                            echo $row['status'] == 'completed' ? 'rgba(76, 175, 80, 0.2)' : 
                                                ($row['status'] == 'cancelled' ? 'rgba(244, 67, 54, 0.2)' : 'rgba(255, 152, 0, 0.2)'); ?>;
                                        color: <?php 
                                            echo $row['status'] == 'completed' ? '#4CAF50' : 
                                                ($row['status'] == 'cancelled' ? '#F44336' : '#FF9800'); ?>;">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 4rem;">
                        <p style="font-size: 1.2rem; margin-bottom: 1.5rem;">You haven't placed any orders yet.</p>
                        <a href="../index.php#menu" class="btn btn-primary">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
