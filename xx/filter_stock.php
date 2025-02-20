<?php
include 'db_connect.php';

$whereClause = "1=1"; // Default condition

if (!empty($_GET['product_name'])) {
 $productName = $_GET['product_name'];
 $whereClause .= " AND p.product_name = '$productName'";
}

if (!empty($_GET['min_price']) && !empty($_GET['max_price'])) {
 $minPrice = $_GET['min_price'];
 $maxPrice = $_GET['max_price'];
// Exception Handling for Price Range: Min price greater than max price
 if ($minPrice > $maxPrice) {
 error_log("Invalid Price Range: Min price ($minPrice) is greater than max price ($maxPrice). Ignoring price filter.");
 // In this case, we simply do NOT add the price filter to the $whereClause
 } else {
 $whereClause .= " AND s.price_perkg BETWEEN $minPrice AND $maxPrice";
 }
}

if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
 $startDate = $_GET['start_date'];
 $endDate = $_GET['end_date'];

// Exception Handling for Date Range: Start date after end date
 if ($startDate > $endDate) {
 error_log("Invalid Date Range: Start date ($startDate) is after end date ($endDate). Ignoring date filter.");
  // In this case, we simply do NOT add the date filter to the $whereClause
 } else {
 $whereClause .= " AND s.date_added BETWEEN '$startDate' AND '$endDate'";
 }
}

$sql = "SELECT s.stock_id, p.product_name, s.quantity, s.price_perkg, (s.quantity * s.price_perkg) AS total,
s.seller_id, se.seller_name, s.date_added
 FROM stocks s
 JOIN products p ON s.product_id = p.product_id
 JOIN sellers se ON s.seller_id = se.seller_id
 WHERE $whereClause
 ORDER BY s.date_added DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
 while ($row = $result->fetch_assoc()) {
 echo "<tr>
<td>{$row['product_name']}</td>
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

$conn->close();
?>