<?php
include 'db_connect.php';

$filterField = isset($_GET['filter_field']) ? $_GET['filter_field'] : '';
$filterValue = isset($_GET['filter_value']) ? $_GET['filter_value'] : '';

$query = "SELECT i.invoice_id, c.customer_name, i.total_price, i.invoice_date 
          FROM invoices i
          JOIN customers c ON i.customer_id = c.customer_id";

if (!empty($filterField) && !empty($filterValue)) {
    switch($filterField) {
        case 'invoice_date':
            $query .= " WHERE DATE(i.invoice_date) = ?";
            break;
        case 'customer_name':
            $query .= " WHERE c.customer_name LIKE ?";
            $filterValue = "%$filterValue%";
            break;
        case 'total_price':
            $query .= " WHERE ROUND(i.total_price, 2) = ROUND(?, 2)";
            break;
    }
}

$stmt = $conn->prepare($query);
if (!empty($filterField) && !empty($filterValue)) {
    $stmt->bind_param('s', $filterValue);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoices</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function updateFilter() {
            const filterType = document.getElementById('filter_type').value;
            const filterValue = document.getElementById('filter_value').value;
            const tableBody = document.querySelector('table tbody');
            
            // Show loading
            tableBody.innerHTML = '<tr><td colspan="4">Loading...</td></tr>';

            fetch(`invoice_view.php?filter_field=${filterType}&filter_value=${filterValue}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.querySelector('table tbody');
                    tableBody.innerHTML = newTableBody ? newTableBody.innerHTML : '<tr><td colspan="4">No results found</td></tr>';
                })
                .catch(error => {
                    tableBody.innerHTML = `<tr><td colspan="4">Error: ${error.message}</td></tr>`;
                });
        }
    </script>
</head>
<body>
    <div class="filters">
        <select id="filter_type" onchange="document.getElementById('filter_value').placeholder = 'Enter ' + this.options[this.selectedIndex].text">
            <option value="">Select Filter</option>
            <option value="invoice_date">Date</option>
            <option value="customer_name">Customer Name</option>
            <option value="total_price">Total Price</option>
        </select>
        <input type="text" id="filter_value" placeholder="Select filter type first">
        <button onclick="updateFilter()">Apply</button>
       <button><a href="invoice.html">Back</a></button> 
    </div>

    <table>
        <tr>
            <th>Invoice ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Date</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['invoice_id'] ?></td>
            <td><?= $row['customer_name'] ?></td>
            <td><?= $row['total_price'] ?></td>
            <td><?= date('m/d/Y', strtotime($row['invoice_date'])) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
