<?php
include 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $sellerName = $data['sellerName'];
    $sellerPhone = $data['sellerPhone'];
    $stockName = $data['stockName'];
    $stockQuantity = $data['stockQuantity'];
    $stockRate = $data['stockRate'];

    $sql = "INSERT INTO stocks (product_id, seller_id, quantity, price_perkg)
            VALUES ((SELECT product_id FROM products WHERE product_name = ?),
                    (SELECT seller_id FROM sellers WHERE seller_phoneno = ?),
                    ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdd", $stockName, $sellerPhone, $stockQuantity, $stockRate);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding stock"]);
    }
}
?>
