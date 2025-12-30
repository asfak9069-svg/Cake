<?php
include 'config/db.php';
$res = $conn->query("SELECT COUNT(*) as count FROM cakes");
$row = $res->fetch_assoc();
echo "Total cakes in database: " . $row['count'] . "\n";
$res = $conn->query("SELECT * FROM cakes");
while($row = $res->fetch_assoc()) {
    echo "- " . $row['cake_name'] . " ($" . $row['price'] . ")\n";
}
?>
