<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ruhara_cakes_db';

try {
    $conn = new mysqli($host, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        $conn->select_db($dbname);
    } else {
        die("Error creating database: " . $conn->error);
    }

} catch (Exception $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>
