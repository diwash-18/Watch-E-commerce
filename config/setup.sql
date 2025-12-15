-- Create database if not exists
CREATE DATABASE IF NOT EXISTS watch_cart;

-- Use the database
USE watch_cart;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some sample products
INSERT INTO products (name, price, image, description) VALUES
('Classic Watch', 199.99, 'watch1.jpg', 'Elegant classic watch with leather strap'),
('Sports Watch', 299.99, 'watch2.jpg', 'Durable sports watch with multiple features'),
('Smart Watch', 399.99, 'watch3.jpg', 'Modern smartwatch with health tracking'),
('Luxury Watch', 599.99, 'watch4.jpg', 'Premium luxury watch with diamond accents'); 