<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cake Menu - Ruhara Cakes</title>
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
        <h2 class="text-wow" style="text-align: center; font-size: 3rem; margin-bottom: 3rem;">Our Delicious Menu</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php
            $result = $conn->query("SELECT * FROM cakes ORDER BY id DESC");
            if ($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            ?>
            <div class="glass-container fade-in" style="overflow: hidden;">
                <img src="../assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['cake_name']; ?>" style="width: 100%; height: 250px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3><?php echo $row['cake_name']; ?></h3>
                    <p style="color: var(--secondary); margin: 0.5rem 0;"><?php echo $row['description']; ?></p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                        <span style="font-size: 1.2rem; font-weight: 700; color: var(--accent);">$<?php echo number_format($row['price'], 2); ?></span>
                        <a href="cake_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 8px 20px;">Details</a>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
                <p style='color: white; text-align: center; grid-column: 1/-1;'>Our ovens are warming up! Check back soon.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
