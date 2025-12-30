<?php
include 'config/db.php';

// Check if tables exist by running the SQL file content
$sql = file_get_contents('database.sql');

if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    echo "<h1>Database and Tables Setup Successfully!</h1>";
    echo "<p>You can now log in as admin at <a href='admin/login.php'>admin/login.php</a></p>";
    echo "<p>Default Credentials: <b>admin</b> / <b>admin123</b></p>";
} else {
    echo "<h1>Setup Failed!</h1>";
    echo "Error: " . $conn->error;
}
?>
