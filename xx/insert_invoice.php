<?php
require 'db.php';

$customer_name = $_POST['customer_name'];
$customer_address = $_POST['customer_address'];
$customer_phone = $_POST['customer_phone'];

$conn->query("INSERT INTO customers (customer_name, customer_address, phone) 
              VALUES ('$customer_name', '$customer_address', '$customer_phone')");

$customer_id = $conn->insert_id;

$conn->query("INSERT INTO invoices (customer_id, total_price) 
              VALUES ('$customer_id', 0)");

$invoice_id = $conn->insert_id;

$total_price = 0;

foreach ($_POST['product_id'] as $index => $product_id) {
    $quantity = $_POST['quantity'][$index];
    $rate = $_POST['rate'][$index];
    $total = $quantity * $rate;
    $total_price += $total;

    $conn->query("INSERT INTO invoice_items (invoice_id, product_id, quantity, rate, total) 
                  VALUES ('$invoice_id', '$product_id', '$quantity', '$rate', '$total')");

    $conn->query("UPDATE stocks SET quantity = quantity - $quantity WHERE product_id = $product_id");
}

$conn->query("UPDATE invoices SET total_price = $total_price WHERE invoice_id = $invoice_id");

header("Location: invoice_view.php");
?>
