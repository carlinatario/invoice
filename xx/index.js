document.addEventListener("DOMContentLoaded", function () {
    let productList = []; // Store product list here
let rowCount = 0;
    loadProducts()
        .then(products => { // loadProducts now returns a Promise
            productList = products;
            addRow(); // Add initial row after products are loaded
        })
        .catch(error => {
            console.error("Error loading products:", error);
            alert("Failed to load products. Please check the console for details."); // User-friendly error
        });

    const tableBody = document.getElementById("table-body");
    


    tableBody.addEventListener("input", function (e) {
        if (e.target.classList.contains("quantity")) {
            if (validateQuantity(e.target)) {
                updateRowTotal(e.target);
            }
        }
        if (e.target.classList.contains("rate")) {
            if (validateRate(e.target)) {
                updateRowTotal(e.target);
            }
        }
    });
    const addButton = document.querySelector(".add-button");
    

    addButton.addEventListener("click", function (e) {
       //alert("Add button clicked!"); // Debugging: Is add button click detected?
        addRow();
    });

    // Replace existing removeButton code with:
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-button')) {
        const row = e.target.closest('tr');
        if (document.querySelectorAll("#table-body tr").length > 1) {
            row.remove();
            updateTotalBill();
        }
    }
});
function validateRate(rateInput) {
    const value = rateInput.value;
    if (isNaN(value) || value <= 0) {
        alert("Rate must be a positive number");
        rateInput.value = "";
        updateRowTotal(rateInput);
        return false;
    }
    return true;
}
    function validateQuantity(quantityInput) {
        const value = quantityInput.value;
        if (isNaN(value) || value === "" || parseFloat(value) < 0) { // Basic validation
            alert("Quantity must be a positive number.");
            quantityInput.value = ""; // Clear invalid input
            updateRowTotal(quantityInput); // Recalculate row total to 0
            updateTotalBill();
            return false;
        }
        return true;
    }


    function loadProducts() {
        return fetch("get_products.php") // Return the Promise
            .then(response => {
                if (!response.ok) { // Check for HTTP errors
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            });
    }


    function updateRowTotal(input) {
        let row = input.closest("tr");
        let quantity = row.querySelector(".quantity").value;
        let rate = row.querySelector(".rate").value;
        let totalPriceCell = row.querySelector(".total-price");

        let total = (parseFloat(quantity) * parseFloat(rate)).toFixed(2); // parseFloat for safety after validation
        totalPriceCell.textContent = isNaN(total) ? "0.00" : total; // Handle NaN case if calculation fails

        updateTotalBill();
    }

    function updateTotalBill() {
        let totalBill = 0;
        document.querySelectorAll(".total-price").forEach(cell => {
            totalBill += parseFloat(cell.textContent) || 0; // Use parseFloat with || 0 to handle empty/non-numeric content safely
        });
        document.getElementById("total-bill").textContent = totalBill.toFixed(2);
    }

    function createTableRow() {
       // Add at the top of your JavaScript

  
        rowCount++; // Increment row count for unique IDs
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
             <button type="button" class="remove-button">-</button>
                <input type="text" name="product_name[]" class="product-name-input" list="product-suggestions-row-${rowCount}" placeholder="Enter product name" required>
                <datalist id="product-suggestions-row-${rowCount}" class="product-suggestions-datalist">
                </datalist>
            </td>
            <td><input type="number" name="quantity[]" class="quantity" min="1" required></td>
            <td><input type="number" name="rate[]" class="rate" min="1" required></td>
            <td class="total-price">0.00</td>
        `;
        console.log("Row HTML created:", row.innerHTML); // Debugging: Inspect the created HTML
        return row;
    }
    
    
    function addRow() {
        
        const newRow = createTableRow();
     
        tableBody.appendChild(newRow);

        attachRowListeners(newRow); // Attach listeners to the new row
        attachSuggestionListener(newRow.querySelector('.product-name-input')); // Attach suggestion listener to product input
       
    }
    function removeRow(button) {
        console.log("removeRow() function called"); // ADD THIS LINE - Debugging: Is removeRow called?
        let row = button.closest("tr");
        if (document.querySelectorAll("#table-body tr").length > 1) {
            row.remove();
            updateTotalBill();
        }
    }

    function attachRowListeners(row) {
        row.querySelectorAll('.quantity, .rate').forEach(input => {
            input.addEventListener('input', () => updateRowTotal(row));
        });
    }


    function attachSuggestionListener(inputField) {
        const datalistId = inputField.getAttribute('list');
        const datalist = document.getElementById(datalistId);

        inputField.addEventListener('input', function() {
            const term = this.value;
            if (term.length >= 2) {
                fetchProductSuggestions(term, datalist);
            } else {
                datalist.innerHTML = ''; // Clear suggestions if input is too short
            }
        });
    }

    function fetchProductSuggestions(term, datalist) {
        fetch(`get_product_suggestions.php?term=${term}`)
            .then(response => response.json())
            .then(data => {
                datalist.innerHTML = ''; // Clear previous suggestions
                data.forEach(productName => {
                    const option = document.createElement('option');
                    option.value = productName;
                    datalist.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
            });
    }


    // Initial row setup
    addRow(); // Add initial row on page load
    updateTotalBill(); // Calculate initial total (which should be 0)
});