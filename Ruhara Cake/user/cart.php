<?php
session_start();
include '../config/db.php';

// Remove item
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// Update quantity
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Ruhara Cakes</title>
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
        <div class="glass-container fade-in" style="padding: 2rem;">
            <h2 class="text-wow" style="margin-bottom: 2rem;">Your Shopping Cart</h2>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div style="text-align: center; color: white; padding: 3rem;">
                    <h3>Your cart is empty!</h3>
                    <p>Go to our <a href="menu.php" style="color: var(--accent);">Menu</a> to find some delicious cakes.</p>
                </div>
            <?php else: ?>
                <form action="cart.php" method="POST">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                                <th style="padding: 15px;">Cake</th>
                                <th style="padding: 15px;">Price</th>
                                <th style="padding: 15px;">Quantity</th>
                                <th style="padding: 15px;">Subtotal</th>
                                <th style="padding: 15px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($_SESSION['cart'] as $id => $qty): 
                                $cake = $conn->query("SELECT * FROM cakes WHERE id = $id")->fetch_assoc();
                                $subtotal = $cake['price'] * $qty;
                                $total += $subtotal;
                            ?>
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td style="padding: 15px; display: flex; align-items: center; gap: 15px;">
                                    <img src="../assets/images/<?php echo $cake['image']; ?>" style="width: 60px; height: 60px; border-radius: 10px; object-fit: cover;">
                                    <span><?php echo $cake['cake_name']; ?></span>
                                </td>
                                <td style="padding: 15px;">$<?php echo number_format($cake['price'], 2); ?></td>
                                <td style="padding: 15px;">
                                    <input type="number" name="quantities[<?php echo $id; ?>]" value="<?php echo $qty; ?>" min="1" max="10" class="form-control" style="width: 80px; padding: 5px 10px;">
                                </td>
                                <td style="padding: 15px;">$<?php echo number_format($subtotal, 2); ?></td>
                                <td style="padding: 15px;">
                                    <a href="cart.php?remove=<?php echo $id; ?>" style="color: #ffcccc;">Remove</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
                        <button type="submit" name="update_cart" class="btn btn-primary" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid var(--glass-border);">Update Cart</button>
                        
                        <div style="text-align: right; color: white;">
                            <h3 style="margin-bottom: 1rem;">Total: <span style="color: var(--accent); font-size: 2rem;">$<?php echo number_format($total, 2); ?></span></h3>
                            <a href="checkout.php" class="btn btn-primary" style="padding: 15px 50px;">Checkout</a>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
