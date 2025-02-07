<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stock Management</title>
    <link rel="stylesheet" href="stock.css">
</head>
<body>
    <h1>Stock Management</h1>
    <!-- Add stock management content here -->
</body>
</html>