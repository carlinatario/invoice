<?php
include 'db_connect.php';

// Default query
$sql = "SELECT s.stock_id, p.product_name, s.quantity, s.price_perkg, (s.quantity * s.price_perkg) AS total, 
               s.seller_id, se.seller_name,se.seller_phoneno, s.date_added 
        FROM stocks s
        JOIN products p ON s.product_id = p.product_id
        JOIN sellers se ON s.seller_id = se.seller_id
        ORDER BY s.date_added DESC";

$result = $conn->query($sql);

// Fetch unique product names for the dropdown
$productQuery = "SELECT DISTINCT product_name FROM products";
$productResult = $conn->query($productQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Records</title>
    <link rel="stylesheet" href="stock.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>
<body>
    <div class="stock-container">
        <div class="header-bar">
            
        <h6><a href="stock.html">Back</a> </h6>
        </div>     
        <header>
            <h1>Shah G Traders - Stock Records</h1>
        </header>

        <!-- Filters -->
        <section class="filters">
            <h2>Filter Stocks</h2>
            <form id="filter-form">
                <label for="filter-type">Select Filter:</label>
                <select id="filter-type">
                    <option value="">-- Choose Filter --</option>
                    <option value="product">Product Name</option>
                    <option value="price">Price Range</option>
                    <option value="date">Date Range</option>
                </select>

                <div id="filter-options">
                    <!-- Dynamic filter inputs will be inserted here -->
                </div>

                <button type="submit">Apply Filter</button>
            </form>
        </section>

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
                        <th>seller_phoneno</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="stock-data">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['product_name']}</td>
                                    <td>{$row['quantity']}</td>
                                    <td>{$row['price_perkg']}</td>
                                    <td>{$row['total']}</td>
                                    <td>{$row['seller_name']}</td>
                                    <td>{$row['seller_phoneno']}</td>
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

    <script>
        $(document).ready(function () {
            // Change filter options dynamically
            $("#filter-type").change(function () {
                let filterType = $(this).val();
                let filterOptions = $("#filter-options");

                filterOptions.html(""); // Clear previous filters
                if (filterType === "product") {
                    filterOptions.html(`<label for="product-name">Product Name:</label>
                        <input type="text" id="product-name" name="product_name" list="product-suggestions" placeholder="Enter product name">
                        <datalist id="product-suggestions">
                                                    </datalist>`);

                    // Add event listener for input changes to fetch suggestions
                    $("#product-name").on('input', function() {
                        let term = $(this).val();
                        if (term.length >= 2) { // Start suggesting after 2 characters
                            $.ajax({
                                url: 'get_product_suggestions.php',
                                type: 'GET',
                                data: { term: term },
                                dataType: 'json', // Expect JSON response
                                success: function(data) {
                                    let suggestions = $("#product-suggestions");
                                    suggestions.empty(); // Clear existing suggestions
                                    $.each(data, function(index, productName) {
                                        suggestions.append(`<option value="${productName}">`);
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error fetching product suggestions:", status, error);
                                }
                            });
                        } else {
                            $("#product-suggestions").empty(); // Clear if less than 2 chars
                        }
                    });
                }
                 else if (filterType === "price") {
                    filterOptions.append(`<label for="min-price">Min Price:</label>
                        <input type="number" id="min-price" name="min_price" min="1" placeholder="Min Price"><br>
                        <label for="max-price">Max Price:</label>
                        <input type="number" id="max-price" name="max_price" min="1" placeholder="Max Price">`);
                } else if (filterType === "date") {
                    filterOptions.append(`<label for="start-date">Start Date:</label>
                        <input type="date" id="start-date" name="start_date"><br>
                        <label for="end-date">End Date:</label>
                        <input type="date" id="end-date" name="end_date">`);
                }
            });

            // Handle form submission with AJAX
            $("#filter-form").on("submit", function (e) {
                e.preventDefault();

                let filterType = $("#filter-type").val();
                let formData = {};

                // Get the selected filter and its value
                if (filterType === "product") {
                    formData.product_name = $("#product-name").val();
                } else if (filterType === "price") {
                    formData.min_price = $("#min-price").val();
                    formData.max_price = $("#max-price").val();
                } else if (filterType === "date") {
                    formData.start_date = $("#start-date").val();
                    formData.end_date = $("#end-date").val();
                }

                $.ajax({
                    url: "filter_stock.php",
                    type: "GET",
                    data: formData,
                    success: function (response) {
                        $("#stock-data").html(response); // Replace table content
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
