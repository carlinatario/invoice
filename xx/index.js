document.addEventListener("DOMContentLoaded", function () {
    let productList = []; // Store product list here

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
    const actionbuttons = document.getElementById("action-buttons");


    tableBody.addEventListener("input", function (e) {
        if (e.target.classList.contains("quantity")) {
            if (validateQuantity(e.target)) { // Validate quantity input
                updateRowTotal(e.target);
                updateTotalBill();
            }
        }
    });

    tableBody.addEventListener("change", function (e) {
        if (e.target.classList.contains("product-select")) {
            
            updateProductRate(e.target);
        }
    });

    actionbuttons.addEventListener("click", function (e) {
        if (e.target.classList.contains("add-button")) {
        console.log("bbbbbbbb");
           // addRow();
        }
        if (e.target.classList.contains("remove-button")) {
            removeRow(e.target);
        }
    });

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

    function updateProductRate(select) {
        let rateField = select.closest("tr").querySelector(".rate");
        let selectedOption = select.options[select.selectedIndex];
        rateField.value = selectedOption.dataset.rate || 0;
        updateRowTotal(select);
    }

    function updateRowTotal(input) {
        let row = input.closest("tr");
        let quantity = row.querySelector(".quantity").value;
        let rate = row.querySelector(".rate").value;
        let totalCell = row.querySelector(".total-price");

        let total = (parseFloat(quantity) * parseFloat(rate)).toFixed(2); // parseFloat for safety after validation
        totalCell.textContent = isNaN(total) ? "0.00" : total; // Handle NaN case if calculation fails

        updateTotalBill();
    }

    function updateTotalBill() {
        let totalBill = 0;
        document.querySelectorAll(".total-price").forEach(cell => {
            totalBill += parseFloat(cell.textContent) || 0; // Use parseFloat with || 0 to handle empty/non-numeric content safely
        });
        document.getElementById("total-bill").textContent = totalBill.toFixed(2);
    }

    function addRow() {
        const firstRow = document.querySelector("#table-body tr");
        const newRow = firstRow.cloneNode(true); // Clone the first row as template
        const selects = newRow.querySelectorAll(".product-select");

        selects.forEach(select => {
            select.innerHTML = `<option value="">Select Product</option>`; // Reset options
            productList.forEach(product => { // Use stored productList
                select.innerHTML += `<option value="${product.product_id}" data-rate="${product.price_perkg}">${product.product_name}</option>`;
            });
        });

        const quantityInput = newRow.querySelector(".quantity");
        quantityInput.value = ""; // Clear quantity
        const totalPriceCell = newRow.querySelector(".total-price");
        totalPriceCell.textContent = "0.00"; // Reset total

        tableBody.appendChild(newRow);
    }

    function removeRow(button) {
        let row = button.closest("tr");
        if (document.querySelectorAll("#table-body tr").length > 1) {
            row.remove();
            updateTotalBill();
        }
    }
});