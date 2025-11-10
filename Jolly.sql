CREATE DATABASE restaurant_db;
USE restaurant_db;

-- Admins Table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Users (Customers)
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

-- Products (Menu)

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    item_id INT,
    quantity INT,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (item_id) REFERENCES menu(id)
);


-- Orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    items TEXT,
    total_amount DECIMAL(10,2),
    status ENUM('Pending','Checked','Paid') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id)
);


-- Sample Menu Items
INSERT INTO products (name, price, stock) VALUES
('Chickenjoy Meal', 120.00, 50),
('Jolly Spaghetti', 80.00, 60),
('Burger Steak', 100.00, 40),
('Jolly Hotdog', 70.00, 50),
('Iced Coffee', 60.00, 50);

-- Add one admin
INSERT INTO admins (username, password) VALUES ('admin', MD5('admin123'));
