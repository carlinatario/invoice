var button = document.getElementById("mybutton");
var tableBody = document.getElementById("table-body");

button.addEventListener("click", function () {
    var newRow = document.createElement("tr");
    for (var i = 0; i < 4; i++) {
        var newCell = document.createElement("td");
        var input = document.createElement("input");
        input.type = "text";
        if (i === 0) input.placeholder = "Product Name";
        if (i === 1) input.placeholder = "Quantity";
        if (i === 2) input.placeholder = "Rate";
        if (i === 3) input.placeholder = "Total";

        newCell.appendChild(input);
        newRow.appendChild(newCell);
    }

    var actionCell = document.createElement("td");
    var removeButton = document.createElement("button");
    removeButton.textContent = "Remove";
    removeButton.className = "remove-button";
    removeButton.addEventListener("click", function () {
        newRow.remove();
    });

    actionCell.appendChild(removeButton);
    newRow.appendChild(actionCell);

    tableBody.appendChild(newRow);
});
tableBody.addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("remove-button")) {
        e.target.closest("tr").remove();
    }
});
