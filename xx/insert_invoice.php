<?php
require 'db_connect.php';

// Start transaction
$conn->begin_transaction();

try {
    // --- Customer Handling ---
    $customer_id = null;
    $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE customer_phoneno = ?");
    $stmt->bind_param("s", $_POST['customer_phoneno']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        $customer_id = $customer['customer_id'];
    } else {
        // Insert new customer with proper prepared statement
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, customer_address, customer_phoneno) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['customer_name'], $_POST['customer_address'], $_POST['customer_phoneno']);
        $stmt->execute();
        $customer_id = $stmt->insert_id;
    }

    // --- Invoice Creation ---
    $stmt = $conn->prepare("INSERT INTO invoices (customer_id, invoice_date, total_price) VALUES (?, NOW(), 0)");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $invoice_id = $stmt->insert_id;

    // --- Process Products ---
    $total_price = 0;

    // Ensure all arrays exist and have the same count
    if (!isset($_POST['product_name'], $_POST['quantity'], $_POST['rate']) ||
        count($_POST['product_name']) !== count($_POST['quantity']) || 
        count($_POST['quantity']) !== count($_POST['rate'])) {
        throw new Exception("Invalid product data format");
    }

    foreach ($_POST['product_name'] as $index => $product_name) {
        // Get product ID
        $stmt = $conn->prepare("SELECT product_id FROM products WHERE product_name = ?");
        $stmt->bind_param("s", $product_name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Product not found: $product_name");
        }
        $product = $result->fetch_assoc();
        $product_id = $product['product_id'];
        
        $quantity = $_POST['quantity'][$index];
        $rate = $_POST['rate'][$index];
        $total = $quantity * $rate;
        $total_price += $total;

        // Check and update stock using atomic update
        $stmt = $conn->prepare("UPDATE stocks SET quantity = quantity - ? WHERE product_id = ? AND quantity >= ?");
        $stmt->bind_param("iii", $quantity, $product_id, $quantity);
        $stmt->execute();
        
        if ($stmt->affected_rows === 0) {
            throw new Exception("Insufficient stock for $product_name");
        }

        // Insert invoice item with prepared statement
        $stmt = $conn->prepare("INSERT INTO invoice_items (invoice_id, product_id, quantity, rate, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidd", $invoice_id, $product_id, $quantity, $rate, $total);
        $stmt->execute();
    }

    // Update invoice total
    $stmt = $conn->prepare("UPDATE invoices SET total_price = ? WHERE invoice_id = ?");
    $stmt->bind_param("di", $total_price, $invoice_id);
    $stmt->execute();

    $conn->commit();
    header("Location: invoice_view.php?id=" . $invoice_id);
    exit();
} catch (Exception $e) {
    $conn->rollback();
    // Proper error handling - redirect or display error
    die("Error: " . $e->getMessage());
}
