CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pizza VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10, 2) NOT NULL,
    gambar VARCHAR(255)
    kategori ENUM('Pizza', 'Snack', 'Drink') NOT NULL DEFAULT 'Pizza'
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin'
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    telepon VARCHAR(20) NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    tanggal_pesan DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pending',
    detail_pesanan TEXT NULL,
    bukti_pembayaran VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, password) VALUES ('admin', '$2y$10$wE/YtJpL70fG9.qD4yR3h.wY/tT0W3.O0H9iJk2h8N0g.P0nS5v7G');