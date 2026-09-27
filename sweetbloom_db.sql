CREATE DATABASE IF NOT EXISTS sweetbloom_db;
USE sweetbloom_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL DEFAULT 'Mix Flower',
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, category, price, stock) VALUES
('Rose Sweet Love', 'Mawar', 150000, 12),
('Sunshine Bouquet', 'Bunga Matahari', 125000, 8),
('Pastel Bloom', 'Mix Flower', 180000, 10),
('Baby Breath Love', 'Baby Breath', 100000, 15);
