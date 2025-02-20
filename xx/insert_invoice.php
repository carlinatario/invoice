<?php
require 'db_connect.php';
// At the VERY START of PHP code
$conn->begin_transaction();

try {
    // ALL your existing database code here
    // Check for existing customer
$stmt = $conn->prepare("SELECT customer_id FROM customers WHERE customer_phoneno = ?");
$stmt->bind_param("s", $_POST['customer_phoneno']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    $customer_id = $customer['customer_id'];
} else {
    // Insert new customer
    // Proper format with explicit column names
$stmt = $conn->prepare("INSERT INTO customers 
    (customer_name, customer_address, customer_phoneno) 
    VALUES (?, ?, ?)");
    // ... rest of insertion code ... $customer_name = $_POST['customer_name'];
$customer_address = $_POST['customer_address'];
$customer_phone = $_POST['customer_phoneno'];

// Replace existing customer insertion code (around line 3-5)
$stmt = $conn->prepare("INSERT INTO customers (customer_name, customer_address, customer_phoneno) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $_POST['customer_name'], $_POST['customer_address'], $_POST['customer_phoneno']);
$stmt->execute();
$customer_id = $stmt->insert_id;

$total_price = 0;
    $customer_id = $stmt->insert_id;
}
   

// Modify the foreach loop (around line 15)
foreach ($_POST['product_name'] as $index => $product_name) {
    // Add this product lookup
    $stmt = $conn->prepare("SELECT product_id FROM products WHERE product_name = ?");
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $product_id = $product['product_id']; 
    // Then use $product_id in your existing code
    $quantity = $_POST['quantity'][$index];
    $rate = $_POST['rate'][$index];
    // Check stock availability
$stmt = $conn->prepare("SELECT quantity FROM stocks WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$stock = $result->fetch_assoc();

if ($stock['quantity'] < $quantity) {
    throw new Exception("Insufficient stock for $product_name");
}
   
    // ... rest of your existing code ...
    foreach ($_POST['product_id'] as $index => $product_id) {
        $quantity = $_POST['quantity'][$index];
        $rate = $_POST['rate'][$index];
        $total = $quantity * $rate;
        $total_price += $total;
    
        $conn->query("INSERT INTO invoice_items (invoice_id, product_id, quantity, rate, total) 
                      VALUES ('$invoice_id', '$product_id', '$quantity', '$rate', '$total')");
    
        $conn->query("UPDATE stocks SET quantity = quantity - $quantity WHERE product_id = $product_id");
    }
    
}

$conn->query("UPDATE invoices SET total_price = $total_price WHERE invoice_id = $invoice_id");

header("Location: invoice_view.php");

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    die("Error: " . $e->getMessage());
}

?>
