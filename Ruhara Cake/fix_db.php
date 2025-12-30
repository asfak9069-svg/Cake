<?php
include 'config/db.php';

echo "Starting migration...\n";

// Add phone and address to users
$sql1 = "ALTER TABLE users ADD COLUMN phone VARCHAR(20), ADD COLUMN address TEXT";
if ($conn->query($sql1)) {
    echo "Columns 'phone' and 'address' added to 'users' table.\n";
} else {
    echo "Error adding columns to users: " . $conn->error . "\n";
}

// Add user_id to orders
$sql2 = "ALTER TABLE orders ADD COLUMN user_id INT";
if ($conn->query($sql2)) {
    echo "Column 'user_id' added to 'orders' table.\n";
} else {
    echo "Error adding column to orders: " . $conn->error . "\n";
}

// Add foreign key
$sql3 = "ALTER TABLE orders ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL";
if ($conn->query($sql3)) {
    echo "Foreign key created for 'user_id' in 'orders' table.\n";
} else {
    echo "Error creating foreign key: " . $conn->error . "\n";
}

echo "Migration finished.\n";
?>
