<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: menu.php");
    exit();
}

$id = $_GET['id'];
$cake = $conn->query("SELECT * FROM cakes WHERE id = $id")->fetch_assoc();

if (!$cake) {
    echo "Cake not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $cake['cake_name']; ?> - Ruhara Cakes</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.3">
    <style>
        .details-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-top: 120px;
            padding: 3rem;
        }
        @media (max-width: 768px) {
            .details-container { grid-template-columns: 1fr; }
        }
    </style>
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

    <div class="details-container glass-container fade-in" style="margin: 120px 5% 5rem;">
        <div>
            <img src="../assets/images/<?php echo $cake['image']; ?>" alt="<?php echo $cake['cake_name']; ?>" style="width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        </div>
        <div style="padding-top: 1rem;">
            <h1 class="text-wow" style="font-size: 3rem; margin-bottom: 1rem;"><?php echo $cake['cake_name']; ?></h1>
            <p style="font-size: 1.8rem; font-weight: 700; margin-bottom: 2rem;">$<?php echo number_format($cake['price'], 2); ?></p>
            
            <h3 style="margin-bottom: 1rem;">Description</h3>
            <p style="font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem;"><?php echo nl2br($cake['description']); ?></p>
            
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="cake_id" value="<?php echo $cake['id']; ?>">
                <div class="form-group" style="max-width: 200px;">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="10">
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem; margin-top: 1rem;">Add to Cart</button>
            </form>
        </div>
    </div>
</body>
</html>
