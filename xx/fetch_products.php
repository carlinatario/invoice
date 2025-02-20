<?php
require "db_connect.php";

$query = "SELECT products.product_id, products.pruduct_name AS product_name, stocks.price_perkg, stocks.quantity AS available_quantity 
          FROM products 
          INNER JOIN stocks ON products.product_id = stocks.product_id 
          WHERE stocks.quantity > 0";

$result = $conn->query($query);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>
