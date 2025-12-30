<?php
session_start();
include 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruhara Cakes - Premium Online Cake Shop</title>
    <link rel="stylesheet" href="assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="index.php" class="logo">🍰 Ruhara Cakes</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="user/menu.php">Menu</a>
            <a href="user/cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="user/dashboard.php">My Account</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php" style="color: var(--accent); font-weight: 700;">Login</a>
                <a href="signup.php" style="background: var(--white); color: var(--primary-dark); padding: 5px 15px; border-radius: 20px; font-weight: 700;">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>

    <section class="hero fade-in">
        <div class="hero-content">
            <h1 class="text-wow" style="font-size: 5rem; margin-bottom: 2rem;">Sweetness in Every Bite</h1>
            <p style="font-size: 1.5rem; margin-bottom: 3rem;">Experience the finest selection of handcrafted cakes, made with love and premium ingredients.</p>
            <div style="display: flex; gap: 1.5rem; justify-content: center;">
                <?php if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])): ?>
                    <a href="user/menu.php" class="btn btn-primary" style="font-size: 1.2rem; background: var(--primary-dark); color: white;">Order Now / View Menu</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary" style="font-size: 1.2rem; background: var(--primary-dark); color: white;">Login to View Menu</a>
                    <a href="signup.php" class="btn btn-primary" style="font-size: 1.2rem; background: var(--white); color: var(--primary-dark);">Register to Join Us</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])): ?>
    <section id="menu" style="padding: 5rem 5%;">
        <h2 class="text-wow" style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem;">Our Bestsellers</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php
            $result = $conn->query("SELECT * FROM cakes LIMIT 6");
            if ($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            ?>
            <div class="glass-container fade-in" style="overflow: hidden; transition: 0.3s;">
                <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['cake_name']; ?>" style="width: 100%; height: 250px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3><?php echo $row['cake_name']; ?></h3>
                    <p style="color: var(--secondary); margin: 0.5rem 0;"><?php echo substr($row['description'], 0, 60); ?>...</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                        <span style="font-size: 1.2rem; font-weight: 700; color: var(--accent);">$<?php echo number_format($row['price'], 2); ?></span>
                        <a href="user/cake_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem;">View Details</a>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
                echo "<p style='color: white; text-align: center; grid-column: 1/-1;'>No cakes available yet. Visit admin panel to add some!</p>";
            endif;
            ?>
        </div>
        
        <div style="text-align: center; margin-top: 4rem;">
            <a href="user/menu.php" class="btn btn-primary" style="background: var(--accent); color: var(--text);">See Full Menu</a>
        </div>
    </section>
    <?php else: ?>
    <section style="padding: 5rem 5%; text-align: center;">
        <h2 class="text-wow" style="font-size: 2rem; margin-bottom: 2rem;">Discover Our Secret Recipes</h2>
        <p style="font-size: 1.2rem; color: white; margin-bottom: 2rem;">Please log in or register to view our exclusive cake collection.</p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
             <a href="login.php" class="btn btn-primary" style="background: var(--primary-dark); color: white;">Login Now</a>
             <a href="signup.php" class="btn btn-primary" style="background: var(--accent); color: var(--text);">Sign Up</a>
        </div>
    </section>
    <?php endif; ?>

    <footer style="text-align: center; padding: 3rem; color: white; background: rgba(0,0,0,0.1);">
        <p>&copy; 2025 Ruhara Cakes. All rights reserved.</p>
        <p style="margin-top: 10px; font-size: 0.8rem; opacity: 0.6;">
            <a href="login.php" style="color: white; text-decoration: none;">Account Login</a>
        </p>
    </footer>
</body>
</html>
