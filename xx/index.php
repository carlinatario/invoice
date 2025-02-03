<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_ROOT_USER', 'root');
define('DB_ROOT_PASS', '');
define('DB_NAME', 'shah_traders');
define('DB_USER', 'st_admin');
define('DB_PASSWORD', 'Tr@d3rs2024!');

// Create database connection
try {
    // Connect as root to create database and user
    $root_conn = new mysqli(DB_HOST, DB_ROOT_USER, DB_ROOT_PASS);
    
    // Create database
    $root_conn->query("CREATE DATABASE IF NOT EXISTS ".DB_NAME." CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Create user and grant privileges
    $root_conn->query("CREATE USER IF NOT EXISTS '".DB_USER."'@'localhost' IDENTIFIED BY '".DB_PASSWORD."'");
    $root_conn->query("GRANT ALL PRIVILEGES ON ".DB_NAME.".* TO '".DB_USER."'@'localhost'");
    $root_conn->query("FLUSH PRIVILEGES");
    
    // Connect with application user
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $conn->set_charset("utf8mb4");

    // Create tables
    $conn->query("CREATE TABLE IF NOT EXISTS invoices (
        invoice_id INT AUTO_INCREMENT PRIMARY KEY,
        client_name VARCHAR(100) NOT NULL,
        client_address TEXT NOT NULL,
        client_phone VARCHAR(20) NOT NULL,
        invoice_date DATE NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS items (
        item_id INT AUTO_INCREMENT PRIMARY KEY,
        invoice_id INT NOT NULL,
        product_name VARCHAR(100) NOT NULL,
        quantity INT NOT NULL,
        rate DECIMAL(10,2) NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE CASCADE
    )");

} catch(Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Insert invoice
        $stmt = $conn->prepare("INSERT INTO invoices 
            (client_name, client_address, client_phone, invoice_date, total_amount) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", 
            $_POST['client_name'],
            $_POST['client_address'],
            $_POST['client_phone'],
            date('Y-m-d'),
            $_POST['total_amount']
        );
        $stmt->execute();
        $invoice_id = $conn->insert_id;
        
        // Insert items
        $item_stmt = $conn->prepare("INSERT INTO items 
            (invoice_id, product_name, quantity, rate, total)
            VALUES (?, ?, ?, ?, ?)");
        
        foreach ($_POST['items'] as $item) {
            $total = $item['quantity'] * $item['rate'];
            $item_stmt->bind_param("isidd", 
                $invoice_id,
                $item['product_name'],
                $item['quantity'],
                $item['rate'],
                $total
            );
            $item_stmt->execute();
        }
        
        $conn->commit();
        echo "Invoice saved successfully! Invoice ID: $invoice_id";
    } catch(Exception $e) {
        $conn->rollback();
        echo "Error saving invoice: " . $e->getMessage();
    }
}
?>