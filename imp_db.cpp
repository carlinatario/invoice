-- Stocks Table (Tracks available stock)
CREATE TABLE stocks (
    stock_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    seller_id INT,
    price_perkg DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES sellers(seller_id) ON DELETE CASCADE
);

-- Invoices Table (Stores invoice records)
CREATE TABLE invoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    total_price DECIMAL(10,2) NOT NULL,
    invoice_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

-- Products Table (Stores product details)
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL
);

-- Sellers Table (Stores seller details)
CREATE TABLE sellers (
    seller_id INT PRIMARY KEY AUTO_INCREMENT,
    seller_name VARCHAR(255) NOT NULL,
    seller_phoneno VARCHAR(20) UNIQUE
);

-- Customers Table (Stores customer details)
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    customer_phoneno VARCHAR(20) UNIQUE,
    customer_address VARCHAR(255) NOT NULL
);

-- Invoice Items Table (Stores products sold in each invoice)
CREATE TABLE invoice_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT,
    product_id INT,
    quantity INT NOT NULL,
    rate DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

