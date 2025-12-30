<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

// Fetch stats
$cake_count = $conn->query("SELECT COUNT(*) as count FROM cakes")->fetch_assoc()['count'];
$order_count = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pending_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ruhara Cakes</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="dashboard.php" class="logo">🍰 Ruhara Cakes Admin</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="add_cake.php">Add Cake</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="dashboard-grid fade-in">
        <div class="glass-container" style="padding: 2rem; text-align: center;">
            <h3 class="text-wow">Total Cakes</h3>
            <p style="font-size: 2.5rem; font-weight: 700;"><?php echo $cake_count; ?></p>
        </div>
        <div class="glass-container" style="padding: 2rem; text-align: center;">
            <h3 class="text-wow">Total Orders</h3>
            <p style="font-size: 2.5rem; font-weight: 700;"><?php echo $order_count; ?></p>
        </div>
        <div class="glass-container" style="padding: 2rem; text-align: center;">
            <h3 class="text-wow">Pending Orders</h3>
            <p style="font-size: 2.5rem; font-weight: 700;"><?php echo $pending_orders; ?></p>
        </div>
    </div>

    <div style="padding: 0 5%; margin-top: 2rem;">
        <div class="glass-container" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 class="text-wow">Manage Cakes</h2>
                <a href="add_cake.php" class="btn btn-primary" style="background: var(--primary-dark); color: white;">Add New Cake</a>
            </div>
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                        <th style="padding: 10px;">Image</th>
                        <th style="padding: 10px;">Name</th>
                        <th style="padding: 10px;">Price</th>
                        <th style="padding: 10px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM cakes ORDER BY id DESC");
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 10px;">
                            <img src="../assets/images/<?php echo $row['image']; ?>" alt="" style="width: 60px; height: 60px; border-radius: 10px; object-fit: cover;">
                        </td>
                        <td style="padding: 10px;"><?php echo $row['cake_name']; ?></td>
                        <td style="padding: 10px;">$<?php echo number_format($row['price'], 2); ?></td>
                        <td style="padding: 10px;">
                            <a href="edit_cake.php?id=<?php echo $row['id']; ?>" style="color: var(--accent); text-decoration: none; margin-right: 15px;">Edit</a>
                            <a href="delete_cake.php?id=<?php echo $row['id']; ?>" style="color: #ffcccc; text-decoration: none;" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
