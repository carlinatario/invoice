<?php
include 'db_connect.php'; // Ensure database connection is established

$sql = "SELECT s.stock_id, p.product_name, s.quantity, s.price_perkg, (s.quantity * s.price_perkg) AS total, s.seller_id, se.seller_name, s.date_added 
        FROM stocks s
        JOIN products p ON s.product_id = p.product_id
        JOIN sellers se ON s.seller_id = se.seller_id
        ORDER BY s.date_added DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Records</title>
    <link rel="stylesheet" href="stock.css">
</head>
<body>
<button><a href="stock.html">Back</a></button> 
        <header>
            <h1>Shah G Traders - Stock Records</h1>
        </header>

        <section class="stock-table">
            <h2>Stock List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Seller</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['pruduct_name']}</td>
                                    <td>{$row['quantity']}</td>
                                    <td>{$row['price_perkg']}</td>
                                    <td>{$row['total']}</td>
                                    <td>{$row['seller_name']}</td>
                                    <td>{$row['date_added']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No stock records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

<?php $conn->close(); ?>
