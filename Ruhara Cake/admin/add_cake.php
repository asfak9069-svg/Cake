<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['cake_name']);
    $price = $_POST['price'];
    $description = $conn->real_escape_string($_POST['description']);
    
    // Image Upload
    $image = $_FILES['cake_image']['name'];
    $target = "../assets/images/" . basename($image);

    $sql = "INSERT INTO cakes (cake_name, price, description, image) VALUES ('$name', '$price', '$description', '$image')";

    if ($conn->query($sql) === TRUE) {
        if (move_uploaded_file($_FILES['cake_image']['tmp_name'], $target)) {
            $message = "Cake added successfully!";
        } else {
            $message = "Cake added, but image upload failed.";
        }
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cake - Ruhara Cakes</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.3">
</head>
<body>
    <div class="navbar glass-container">
        <a href="dashboard.php" class="logo">🍰 Ruhara Cakes Admin</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="add_cake.php" style="color: var(--accent);">Add Cake</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="form-card glass-container fade-in" style="max-width: 600px;">
        <h2 class="text-wow" style="text-align: center; margin-bottom: 2rem;">Add New Cake</h2>
        
        <?php if($message): ?>
            <div style="color: <?php echo strpos($message, 'Error') !== false ? '#ffcccc' : '#ccffcc'; ?>; margin-bottom: 1.5rem; text-align: center;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="add_cake.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="cake_name">Cake Name</label>
                <input type="text" name="cake_name" id="cake_name" class="form-control" placeholder="e.g. Chocolate Truffle" required>
            </div>
            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="19.99" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Describe the cake..."></textarea>
            </div>
            <div class="form-group">
                <label for="cake_image">Cake Image</label>
                <input type="file" name="cake_image" id="cake_image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Add Cake</button>
        </form>
    </div>
</body>
</html>
