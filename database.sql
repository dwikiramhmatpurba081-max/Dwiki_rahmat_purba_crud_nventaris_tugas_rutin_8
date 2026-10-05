CREATE DATABASE IF NOT EXISTS inventaris_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE inventaris_db;

CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS suppliers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    address VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    price DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    category_id INT UNSIGNED NOT NULL,
    supplier_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id)
        REFERENCES categories (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_products_supplier FOREIGN KEY (supplier_id)
        REFERENCES suppliers (id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT IGNORE INTO categories (id, name, description) VALUES
    (1, 'Elektronik', 'Perangkat elektronik dan komputer'),
    (2, 'Alat Tulis', 'Perlengkapan tulis dan kantor'),
    (3, 'Furniture', 'Perabot dan perlengkapan ruang'),
    (4, 'Aksesori', 'Aksesori komputer dan perangkat'),
    (5, 'Jaringan', 'Perangkat jaringan dan konektivitas');

INSERT IGNORE INTO suppliers (id, name, phone, email, address) VALUES
    (1, 'PT Sumber Teknologi', '021-5550101', 'sales@sumberteknologi.test', 'Jakarta'),
    (2, 'CV Karya Office', '022-5550102', 'info@karyaoffice.test', 'Bandung'),
    (3, 'PT Furni Nusantara', '024-5550103', 'halo@furninusantara.test', 'Semarang'),
    (4, 'CV Digital Jaya', '031-5550104', 'kontak@digitaljaya.test', 'Surabaya'),
    (5, 'PT Koneksi Prima', '061-5550105', 'sales@koneksiprima.test', 'Medan');

INSERT IGNORE INTO products (id, name, sku, price, stock, category_id, supplier_id) VALUES
    (1, 'Laptop Kerja 14 Inci', 'ELK-001', 8500000.00, 12, 1, 1),
    (2, 'Paket Alat Tulis Kantor', 'ATK-001', 125000.00, 45, 2, 2),
    (3, 'Kursi Kerja Ergonomis', 'FUR-001', 1750000.00, 8, 3, 3),
    (4, 'Mouse Wireless', 'AKS-001', 185000.00, 30, 4, 4),
    (5, 'Router Gigabit Dual Band', 'JAR-001', 625000.00, 16, 5, 5),
    (6, 'Monitor LED 24 Inci', 'ELK-002', 2100000.00, 10, 1, 1),
    (7, 'Meja Kerja Minimalis', 'FUR-002', 1350000.00, 6, 3, 3),
    (8, 'Keyboard USB', 'AKS-002', 145000.00, 22, 4, 4),
    (9, 'Switch 8 Port', 'JAR-002', 340000.00, 14, 5, 5),
    (10, 'Buku Catatan A5', 'ATK-002', 28000.00, 60, 2, 2);
