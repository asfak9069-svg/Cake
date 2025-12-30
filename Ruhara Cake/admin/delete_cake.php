<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get image name to delete from folder
    $result = $conn->query("SELECT image FROM cakes WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        $image_path = "../assets/images/" . $row['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    $conn->query("DELETE FROM cakes WHERE id = $id");
}

header("Location: dashboard.php");
exit();
?>
