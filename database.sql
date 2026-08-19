-- Buat database
CREATE DATABASE ecommerce_db;

-- Tabel Users
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    address TEXT,
    phone VARCHAR(15),
    role VARCHAR(20) DEFAULT 'user', -- 'admin' atau 'user'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Products
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INTEGER DEFAULT 0,
    image_url TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Orders
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending', -- pending, paid, shipped, completed, cancelled
    shipping_address TEXT,
    payment_method VARCHAR(50)
);

-- Tabel Order Items
CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

-- Data awal
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@email.com', md5('admin123'), 'Administrator', 'admin');

INSERT INTO products (name, description, price, stock) VALUES
('Kaos Polos', 'Kaos katun berkualitas', 50000, 10),
('Celana Jeans', 'Celana jeans slim fit', 150000, 5),
('Jaket Hoodie', 'Hoodie nyaman dan hangat', 200000, 3),
('Topi Baseball', 'Topi model terbaru', 75000, 8),
('Tas Ransel', 'Tas ransel casual', 180000, 4);