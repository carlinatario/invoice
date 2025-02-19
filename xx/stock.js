document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('stock-form').addEventListener('sumbmit', function (event) {
        event.preventDefault();

        let stockData = {
            sellerName: document.getElementById('seller-name').value.trim(),
            sellerPhone: document.getElementById('seller-phoneno').value.trim(),
            stockName: document.getElementById('stock-name').value.trim(),
            stockQuantity: document.getElementById('stock-quantity').value.trim(),
            stockRate: document.getElementById('stock-rate').value.trim()
        };

        fetch('insert_stock.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(stockData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Stock added successfully!');
                document.getElementById('stock-form').reset();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
