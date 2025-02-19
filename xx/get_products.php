<?php
require 'db.php'; // Connect to the database

$query = "SELECT s.product_id, p.product_name, s.price_perkg 
          FROM stocks s
          JOIN products p ON s.product_id = p.product_id
          WHERE s.quantity > 0";

$result = $conn->query($query);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>
