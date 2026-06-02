-- MySQL schema for Muzammil Lace Center
CREATE DATABASE IF NOT EXISTS muzammil_lace_center DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE muzammil_lace_center;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    phone VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT NULL,
    city VARCHAR(100) NULL,
    postal_code VARCHAR(25) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admin (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    sale_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    description TEXT,
    short_description VARCHAR(400),
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    rating DECIMAL(3,2) NOT NULL DEFAULT 4.80,
    reviews_count INT UNSIGNED NOT NULL DEFAULT 0,
    color VARCHAR(80) NULL,
    material VARCHAR(120) NULL,
    width VARCHAR(80) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS product_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    `order` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    fullname VARCHAR(150) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    email VARCHAR(180) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code VARCHAR(25) NOT NULL,
    payment_method VARCHAR(60) NOT NULL,
    coupon_code VARCHAR(80) NULL,
    discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    shipping_charge DECIMAL(10,2) NOT NULL DEFAULT 0,
    tax_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    status VARCHAR(60) NOT NULL DEFAULT 'Processing',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS wishlist (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_product (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
    message TEXT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS blog_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(250) NOT NULL,
    excerpt VARCHAR(500) NULL,
    content TEXT NOT NULL,
    author VARCHAR(120) NOT NULL DEFAULT 'Muzammil Lace Center',
    status TINYINT(1) NOT NULL DEFAULT 1,
    published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL,
    subject VARCHAR(220) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS coupons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(80) NOT NULL UNIQUE,
    discount_type ENUM('fixed','percent') NOT NULL DEFAULT 'percent',
    discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    expiry_date DATE NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO admin (name, email, password) VALUES ('Site Admin', 'admin@muzammillacecenter.com', '$2y$12$51j39FzTBd40bi0NbP.T/O0XwHjyoCFgKV5nnkRqp5rpUrHUFFUH.');

INSERT IGNORE INTO categories (name) VALUES
('Bridal Laces'),
('Fancy Laces'),
('Imported Laces'),
('Embroidered Borders'),
('Decorative Ribbons'),
('Garment Accessories'),
('Sewing Materials'),
('Fashion Trims');

INSERT IGNORE INTO products (category_id, name, price, sale_price, description, short_description, stock, color, material, width, status) VALUES
(1, 'Premium Bridal Lace Set', 1850.00, 1450.00, 'Premium bridal lace suitable for wedding gowns and couture designs.', 'Elegant bridal lace for couture gowns.', 40, 'Ivory', 'Polyester', '6 inch', 1),
(2, 'Fancy Floral Lace Trim', 950.00, 780.00, 'Fancy lace trim with floral motifs for upscale apparel.', 'Floral lace trim with rich detailing.', 60, 'Gold', 'Mixed Fiber', '4 inch', 1),
(3, 'Imported Lace Panel', 2200.00, 1699.00, 'Imported lace pattern for premium wedding embellishment.', 'Imported luxury lace panel.', 30, 'Cream', 'Nylon', '12 inch', 1),
(4, 'Embroidered Border Ribbon', 820.00, 690.00, 'Intricate embroidered border perfect for sarees and dresses.', 'Hand-finished embroidered borders.', 75, 'Maroon', 'Rayon', '3 inch', 1),
(5, 'Decorative Satin Ribbon', 320.00, 250.00, 'Shiny decorative ribbon ideal for gift wrapping and fashion accents.', 'Luxury satin decorative ribbon.', 120, 'Red', 'Satin', '2 inch', 1),
(6, 'Garment Accessory Pack', 1550.00, 1299.00, 'Complete accessory pack for fashion stitching and finishing.', 'Essential garment accessories pack.', 50, 'Mixed', 'Assorted', 'Various', 1),
(7, 'Sewing Material Bundle', 1350.00, 1140.00, 'Value bundle of sewing essentials and trims for designers.', 'Sewing material and trims bundle.', 90, 'Beige', 'Mixed', 'Various', 1),
(8, 'Fashion Trims Set', 1050.00, 899.00, 'Elegant fashion trims set for couture detailing.', 'Designer fashion trims set.', 70, 'Champagne', 'Polyester', '5 inch', 1);

INSERT IGNORE INTO coupons (code, discount_type, discount_amount, expiry_date, status) VALUES
('WEDDING25', 'percent', 25.00, DATE_ADD(CURRENT_DATE(), INTERVAL 90 DAY), 1),
('EIDSALE', 'percent', 20.00, DATE_ADD(CURRENT_DATE(), INTERVAL 60 DAY), 1),
('BULK10', 'fixed', 300.00, DATE_ADD(CURRENT_DATE(), INTERVAL 120 DAY), 1);

INSERT IGNORE INTO blog_posts (title, excerpt, content, author, status, published_at, created_at) VALUES
('Bridal Fashion Trends for 2026', 'Discover the newest bridal lace styles shaping wedding wear this season.', 'Explore the upcoming bridal fashion trends, lace textures, and ensemble details for 2026.', 'Muzammil Lace Center', 1, NOW(), NOW()),
('Lace Styling Ideas for Festive Wear', 'Ideas to style premium lace for Eid, weddings, and celebrations.', 'Learn styling techniques for combining lace with ribbons, borders, and accessories.', 'Muzammil Lace Center', 1, NOW(), NOW()),
('Embroidery Designs & Sewing Tips', 'Expert embroidery design ideas and professional sewing tips.', 'Discover embroidery designs that pair beautifully with lace and trimming materials.', 'Muzammil Lace Center', 1, NOW(), NOW());