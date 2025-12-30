<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

$message = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $cake = $conn->query("SELECT * FROM cakes WHERE id = $id")->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $conn->real_escape_string($_POST['cake_name']);
    $price = $_POST['price'];
    $description = $conn->real_escape_string($_POST['description']);
    
    // Check if new image is uploaded
    if (!empty($_FILES['cake_image']['name'])) {
        $image = $_FILES['cake_image']['name'];
        $target = "../assets/images/" . basename($image);
        move_uploaded_file($_FILES['cake_image']['tmp_name'], $target);
        $sql = "UPDATE cakes SET cake_name='$name', price='$price', description='$description', image='$image' WHERE id=$id";
    } else {
        $sql = "UPDATE cakes SET cake_name='$name', price='$price', description='$description' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "Cake updated successfully!";
        // Refresh cake data
        $cake = $conn->query("SELECT * FROM cakes WHERE id = $id")->fetch_assoc();
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
    <title>Edit Cake - Ruhara Cakes</title>
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

    <div class="form-card glass-container fade-in" style="max-width: 600px;">
        <h2 class="text-wow" style="text-align: center; margin-bottom: 2rem;">Edit Cake</h2>
        
        <?php if($message): ?>
            <div style="color: <?php echo strpos($message, 'Error') !== false ? '#ffcccc' : '#ccffcc'; ?>; margin-bottom: 1.5rem; text-align: center;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="edit_cake.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $cake['id']; ?>">
            <div class="form-group">
                <label for="cake_name">Cake Name</label>
                <input type="text" name="cake_name" id="cake_name" class="form-control" value="<?php echo $cake['cake_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?php echo $cake['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"><?php echo $cake['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Current Image</label><br>
                <img src="../assets/images/<?php echo $cake['image']; ?>" style="width: 100px; border-radius: 10px; margin-bottom: 10px;">
                <label for="cake_image">Change Image (Optional)</label>
                <input type="file" name="cake_image" id="cake_image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Update Cake</button>
        </form>
    </div>
</body>
</html>
