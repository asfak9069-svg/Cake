<?php
session_start();
include 'config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if username exists
    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $error = "Username already exists!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, role, phone, address) VALUES ('$username', '$hashed_password', 'customer', '$phone', '$address')";
        
        if ($conn->query($sql)) {
            $success = "Registration successful! You can now <a href='login.php' style='color: var(--accent);'>login</a>.";
        } else {
            $error = "Registration failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Signup - Ruhara Cakes</title>
    <link rel="stylesheet" href="assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="index.php" class="logo">🍰 Ruhara Cakes</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="user/menu.php">Menu</a>
            <a href="login.php">Login</a>
        </div>
    </div>

    <div style="padding-top: 100px;">
        <div class="form-card glass-container fade-in">
            <h2 class="text-wow" style="text-align: center; margin-bottom: 2rem;">Create Account</h2>
            
            <?php if($error): ?>
                <div style="color: #ffcccc; margin-bottom: 1rem; text-align: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 10px;"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div style="color: #ccffcc; margin-bottom: 1rem; text-align: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 10px;"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="signup.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="address">Delivery Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; background: var(--primary-dark); color: white;">Sign Up</button>
            </form>
            
            <p style="text-align: center; margin-top: 1.5rem;">
                Already have an account? <a href="login.php" style="color: var(--primary-dark); font-weight: 600;">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
