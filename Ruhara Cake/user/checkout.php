<?php
session_start();
include '../config/db.php';

$user_data = null;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $user_data = $conn->query("SELECT * FROM users WHERE id = $uid")->fetch_assoc();
}

if (empty($_SESSION['cart'])) {
    header("Location: menu.php");
    exit();
}

$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $cake = $conn->query("SELECT price FROM cakes WHERE id = $id")->fetch_assoc();
    $total += $cake['price'] * $qty;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Ruhara Cakes</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="../index.php" class="logo">🍰 Ruhara Cakes</a>
        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">My Account</a>
                <a href="../logout.php">Logout</a>
            <?php else: ?>
                <a href="../login.php" style="color: var(--accent); font-weight: 700;">Login</a>
                <a href="../signup.php" style="background: var(--white); color: var(--primary-dark); padding: 5px 15px; border-radius: 20px; font-weight: 700;">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>

    <div style="padding: 120px 5% 5rem;">
        <div class="form-card glass-container fade-in" style="max-width: 700px;">
            <h2 class="text-wow" style="text-align: center; margin-bottom: 2rem;">Checkout Details</h2>
            
            <form action="place_order.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Your name" value="<?php echo $user_data ? $user_data['username'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Your phone" value="<?php echo $user_data ? $user_data['phone'] : ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Delivery Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter your full delivery address" required><?php echo $user_data ? $user_data['address'] : ''; ?></textarea>
                </div>

                <div style="background: rgba(255,255,255,0.2); padding: 1.5rem; border-radius: 15px; margin-top: 1rem;">
                    <h4 style="margin-bottom: 0.5rem;">Order Summary</h4>
                    <p style="display: flex; justify-content: space-between;">
                        <span>Total Items:</span>
                        <span><?php echo array_sum($_SESSION['cart']); ?></span>
                    </p>
                    <p style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.2rem; margin-top: 0.5rem;">
                        <span>Final Bill:</span>
                        <span style="color: var(--accent);">$<?php echo number_format($total, 2); ?></span>
                    </p>
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 2rem; padding: 15px;">Place Order</button>
            </form>
        </div>
    </div>
</body>
</html>
