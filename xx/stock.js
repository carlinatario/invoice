document.getElementById('save-stock').addEventListener('click', function() {
    const name = document.getElementById('stock-name').value;
    const quantity = parseFloat(document.getElementById('stock-quantity').value);
    const rate = parseFloat(document.getElementById('stock-rate').value);
    const date = new Date().toLocaleDateString();

    if (name && !isNaN(quantity) && !isNaN(rate)) {
        const total = quantity * rate;

        const tableBody = document.getElementById('stock-table-body');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>${name}</td>
            <td>${quantity}</td>
            <td>${rate}</td>
            <td>${total.toFixed(2)}</td>
            <td>${date}</td>
        `;

        tableBody.appendChild(newRow);

        // Update grand total
        const grandTotalElement = document.getElementById('grand-total');
        const currentGrandTotal = parseFloat(grandTotalElement.textContent);
        const newGrandTotal = currentGrandTotal + total;
        grandTotalElement.textContent = newGrandTotal.toFixed(2);

        // Clear input fields
        document.getElementById('stock-name').value = '';
        document.getElementById('stock-quantity').value = '';
        document.getElementById('stock-rate').value = '';
    } else {
        alert('Please fill out all fields with valid numbers before saving.');
    }
});