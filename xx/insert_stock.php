<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sellerName = $_POST['sellerName'];
    $sellerPhone = $_POST['sellerPhone'];
    $stockName = $_POST['stockName'];
    $stockQuantity = $_POST['stockQuantity'];
    $stockRate = $_POST['stockRate'];

    // 1. Check if seller exists
    $seller_id = null;
    $checkSellerSql = "SELECT seller_id FROM sellers WHERE seller_phoneno = ?";
    $checkSellerStmt = $conn->prepare($checkSellerSql);
    $checkSellerStmt->bind_param("s", $sellerPhone);
    $checkSellerStmt->execute();
    $checkSellerResult = $checkSellerStmt->get_result();

    if ($checkSellerResult->num_rows > 0) {
        $sellerRow = $checkSellerResult->fetch_assoc();
        $seller_id = $sellerRow['seller_id'];
    } else {
        // Seller doesn't exist, insert new seller
        $insertSellerSql = "INSERT INTO sellers (seller_name, seller_phoneno) VALUES (?, ?)";
        $insertSellerStmt = $conn->prepare($insertSellerSql);
        $insertSellerStmt->bind_param("ss", $sellerName, $sellerPhone);
        if ($insertSellerStmt->execute()) {
            $seller_id = $insertSellerStmt->insert_id; // Get the ID of the newly inserted seller
        } else {
            echo "Error adding seller: " . $insertSellerStmt->error;
            exit(); // Stop execution if seller insertion fails
        }
    }
    $checkSellerStmt->close(); // Close the check seller statement

    // 2. Check if product exists
    $product_id = null;
    $checkProductSql = "SELECT product_id FROM products WHERE product_name = ?";
    $checkProductStmt = $conn->prepare($checkProductSql);
    $checkProductStmt->bind_param("s", $stockName);
    $checkProductStmt->execute();
    $checkProductResult = $checkProductStmt->get_result();

    if ($checkProductResult->num_rows > 0) {
        $productRow = $checkProductResult->fetch_assoc();
        $product_id = $productRow['product_id'];
    } else {
        // Product doesn't exist, insert new product
        $insertProductSql = "INSERT INTO products (product_name) VALUES (?)";
        $insertProductStmt = $conn->prepare($insertProductSql);
        $insertProductStmt->bind_param("s", $stockName);
        if ($insertProductStmt->execute()) {
            $product_id = $insertProductStmt->insert_id; // Get the ID of the newly inserted product
        } else {
            echo "Error adding product: " . $insertProductStmt->error;
            exit(); // Stop execution if product insertion fails
        }
    }
    $checkProductStmt->close(); // Close the check product statement

    // 3. Insert stock (using retrieved or newly created seller_id and product_id)
    if ($seller_id !== null && $product_id !== null) {
        $sql = "INSERT INTO stocks (product_id, seller_id, quantity, price_perkg) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $product_id, $seller_id, $stockQuantity, $stockRate); // Use 'i' for integer IDs

        if ($stmt->execute()) {
            echo "Stock added successfully!";
            header("location: stock.html");

        } else {
            echo "Error adding stock: " . $stmt->error;
        }
        $stmt->close(); // Close the insert stock statement
    } else {
        echo "Could not retrieve or create seller or product IDs."; // Should not reach here unless there's a logic error
    }

} else {
    echo "Invalid request method.";
}

$conn->close();
?>