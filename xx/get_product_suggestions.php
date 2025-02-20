<?php
include 'db_connect.php';

if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];

    // SQL query to fetch product names that start with or contain the search term
    $sql = "SELECT product_name FROM products WHERE product_name LIKE ? LIMIT 10"; // Limit to 10 suggestions

    $stmt = $conn->prepare($sql);
    $searchTermWithWildcard = $searchTerm . '%'; // For "starts with" matching
    //$searchTermWithWildcard = '%' . $searchTerm . '%'; // For "contains" matching - use this if you want suggestions that contain the term anywhere in the name
    $stmt->bind_param("s", $searchTermWithWildcard);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = array();
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['product_name'];
    }

    header('Content-Type: application/json'); // Set response header to JSON
    echo json_encode($suggestions); // Encode suggestions array to JSON and output

    $stmt->close();
    $conn->close();
} else {
    // If term is not set, return empty JSON array
    header('Content-Type: application/json');
    echo json_encode(array_map('htmlspecialchars', $suggestions));
    $conn->close(); //Close connection even if no term
}
?>