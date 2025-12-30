<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

// Update Status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
}

$orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Ruhara Cakes</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="dashboard.php" class="logo">🍰 Ruhara Cakes Admin</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="add_cake.php">Add Cake</a>
            <a href="orders.php" style="color: var(--accent);">Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div style="padding: 100px 5% 2rem;">
        <div class="glass-container" style="padding: 2rem;">
            <h2 class="text-wow" style="margin-bottom: 2rem;">Customer Orders</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                        <th style="padding: 15px;">Order ID</th>
                        <th style="padding: 15px;">Customer</th>
                        <th style="padding: 15px;">Total</th>
                        <th style="padding: 15px;">Date</th>
                        <th style="padding: 15px;">Status</th>
                        <th style="padding: 15px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $orders->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 15px;">#<?php echo $row['id']; ?></td>
                        <td style="padding: 15px;">
                            <b><?php echo $row['customer_name']; ?></b><br>
                            <small><?php echo $row['phone']; ?></small>
                        </td>
                        <td style="padding: 15px;">$<?php echo number_format($row['total'], 2); ?></td>
                        <td style="padding: 15px;"><?php echo date('M d, Y', strtotime($row['order_date'])); ?></td>
                        <td style="padding: 15px;">
                            <span style="padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; background: <?php 
                                echo $row['status'] == 'pending' ? 'rgba(255, 215, 0, 0.2)' : ($row['status'] == 'completed' ? 'rgba(144, 238, 144, 0.2)' : 'rgba(255, 99, 71, 0.2)');
                            ?>; color: <?php 
                                echo $row['status'] == 'pending' ? '#FFD700' : ($row['status'] == 'completed' ? '#90EE90' : '#FF6347');
                            ?>;">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <form action="orders.php" method="POST" style="display: flex; gap: 10px;">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-control" style="padding: 5px; width: 120px;">
                                    <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                                    <option value="completed" <?php if($row['status']=='completed') echo 'selected'; ?>>Completed</option>
                                    <option value="cancelled" <?php if($row['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary" style="padding: 5px 15px; border-radius: 5px;">Update</button>
                            </form>
                            <a href="view_order.php?id=<?php echo $row['id']; ?>" style="color: var(--accent); margin-top: 5px; display: inline-block;">Details</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
