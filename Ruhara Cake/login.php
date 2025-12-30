<?php
session_start();
include 'config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Search for user regardless of role
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['role'] == 'admin') {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_username'] = $user['username'];
                header("Location: index.php");
                exit();
            }
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ruhara Cakes</title>
    <link rel="stylesheet" href="assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="index.php" class="logo">🍰 Ruhara Cakes</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="user/menu.php">Menu</a>
            <a href="signup.php">Signup</a>
        </div>
    </div>

    <div style="padding-top: 100px;">
        <div class="form-card glass-container fade-in">
            <h2 class="text-wow" style="text-align: center; margin-bottom: 2rem;">Secure Login</h2>
            
            <?php if($error): ?>
                <div style="color: #ffcccc; margin-bottom: 1rem; text-align: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 10px;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; background: var(--primary-dark); color: white;">Login</button>
            </form>
            
            <p style="text-align: center; margin-top: 1.5rem;">
                Don't have an account? <a href="signup.php" style="color: var(--primary-dark); font-weight: 600;">Sign up here</a>
            </p>
        </div>
    </div>
</body>
</html>
