-- ==============================================
-- DATABASE: alice_collection
-- Run this in phpMyAdmin or MySQL CLI
-- ==============================================

CREATE DATABASE IF NOT EXISTS alice_collection;
USE alice_collection;

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    image VARCHAR(255),
    parent_id INT DEFAULT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    old_price DECIMAL(10,2) DEFAULT NULL,
    category_id INT,
    image VARCHAR(255),
    images TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    is_new BOOLEAN DEFAULT FALSE,
    is_flash_sale BOOLEAN DEFAULT FALSE,
    flash_sale_end DATETIME DEFAULT NULL,
    stock INT DEFAULT 10,
    size_options TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_email VARCHAR(255),
    customer_address TEXT NOT NULL,
    city VARCHAR(100),
    delivery_fee DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('mpesa', 'cash_on_delivery') DEFAULT 'mpesa',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    order_status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    mpesa_code VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Testimonials table
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_image VARCHAR(255),
    rating INT DEFAULT 5,
    comment TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert categories
INSERT INTO categories (name, slug, display_order) VALUES
('Men\'s Clothing', 'mens-clothing', 1),
('Women\'s Clothing', 'womens-clothing', 2),
('Shoes & Sneakers', 'shoes', 3),
('Handbags', 'handbags', 4),
('Watches', 'watches', 5),
('Accessories', 'accessories', 6);

-- Insert sample products
INSERT INTO products (name, slug, description, price, old_price, category_id, image, is_featured, is_new, stock) VALUES
('Premium Leather Jacket', 'premium-leather-jacket', 'Classic black leather jacket for men, perfect for any occasion. Made with genuine leather.', 12500.00, 15000.00, 1, 'product1.jpg', TRUE, TRUE, 15),
('Elegant Evening Gown', 'elegant-evening-gown', 'Stunning gold evening gown with exquisite detailing. Perfect for special occasions.', 18500.00, 22000.00, 2, 'product2.jpg', TRUE, TRUE, 8),
('Air Max Running Shoes', 'air-max-running-shoes', 'Comfortable and stylish running shoes with excellent cushioning.', 8900.00, 12000.00, 3, 'product3.jpg', TRUE, FALSE, 25),
('Designer Handbag', 'designer-handbag', 'Luxury leather handbag with gold accents. Spacious and elegant.', 15500.00, 18500.00, 4, 'product4.jpg', TRUE, TRUE, 12),
('Gold Chronograph Watch', 'gold-chronograph-watch', 'Elegant gold stainless steel watch with chronograph function.', 25000.00, 30000.00, 5, 'product5.jpg', TRUE, FALSE, 5),
('Silk Necktie Set', 'silk-necktie-set', 'Premium silk necktie with matching pocket square. Comes in a gift box.', 4500.00, 6500.00, 6, 'product6.jpg', FALSE, TRUE, 30),
('Casual Summer Dress', 'casual-summer-dress', 'Light and breezy summer dress. Perfect for hot Kenyan days.', 6500.00, NULL, 2, 'product7.jpg', FALSE, TRUE, 20),
('Leather Sneakers White', 'leather-sneakers-white', 'Premium white leather sneakers. Versatile and comfortable.', 10500.00, 13500.00, 3, 'product8.jpg', TRUE, FALSE, 18);

-- Insert testimonials
INSERT INTO testimonials (customer_name, comment, rating) VALUES
('John Mwangi', 'Amazing quality clothes! The leather jacket I bought is top notch. Fast delivery to Nairobi.', 5),
('Sarah Wanjiku', 'Alice Collection has the best women\'s fashion in Kiambu. The evening gown is stunning!', 5),
('Peter Omondi', 'Great customer service and authentic products. Will definitely shop again.', 4),
('Maryanne Njeri', 'Love the handbags! So elegant and affordable. M-Pesa payment was seamless.', 5);

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin_users (username, password, role, is_active) VALUES
('admin', '$2y$12$4R38I1uQF.bA9JU7h91iF.9bL0o2NARsE8gdXjC7A6Orrs.8bEFM6', 'super_admin', 1);