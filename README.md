# CRUD Inventaris

Aplikasi inventaris sederhana dengan PHP, MySQL, dan PDO. Dibuat untuk memenuhi tugas rutin CRUD: tiga tabel berelasi, koneksi PDO Singleton, prepared statements, dropdown kategori dan supplier, daftar produk dengan JOIN, create/update/delete, pesan flash, escaping HTML, pencarian, dan pagination.

## Persyaratan

- PHP 8.1 atau lebih baru dengan ekstensi `pdo_mysql`
- MySQL 8.0 atau MariaDB 10.4 atau lebih baru
- XAMPP direkomendasikan untuk Windows

## Menjalankan di XAMPP

1. Salin folder proyek ke `C:\xampp\htdocs\crud-inventaris`.
2. Jalankan modul **Apache** dan **MySQL** dari XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`, pilih tab **Import**, lalu impor file `database.sql` dari folder proyek. File ini membuat database, tiga tabel, dan contoh data.
4. Buka `http://localhost/crud-inventaris/`.

Pengaturan koneksi default: host `127.0.0.1`, database `inventaris_db`, user `root`, dan password kosong. Untuk nilai berbeda, atur environment variable `DB_HOST`, `DB_NAME`, `DB_USER`, dan `DB_PASSWORD` sebelum menjalankan PHP.

## Struktur database

- `categories`: data kategori produk.
- `suppliers`: data pemasok.
- `products`: produk, dengan foreign key ke kategori dan supplier.

Setiap tabel memiliki minimal lima baris data awal. Hapus produk dilakukan dalam transaksi; foreign key kategori dan supplier mencegah referensi yang tidak valid. SKU harus unik.

## Catatan fitur

- Semua input database memakai prepared statements.
- Token CSRF digunakan pada operasi create, update, dan delete.
- Nilai dinamis yang ditampilkan di HTML di-escape dengan `htmlspecialchars()`.
- Pencarian mencakup nama produk, kategori, dan supplier; daftar produk memakai `JOIN`.
- Hapus meminta konfirmasi browser sebelum mengirim form.
