# 🌷 SweetBloom - Mini Project Pemrograman Web

SweetBloom adalah aplikasi web sederhana untuk mengelola data buket bunga menggunakan PHP, MySQL, PDO, HTML, dan CSS.

## Fitur
- Create / tambah buket
- Read / menampilkan buket
- Update / edit buket
- Delete / hapus buket
- Validasi server-side
- Prepared statement PDO
- PRG setelah tambah/edit
- CSRF protection pada delete
- htmlspecialchars untuk keamanan output
- Search dan filter kategori
- Responsive UI dengan Flexbox

## Struktur
sweetbloom/
├── config/
│   └── db.php
├── database/
│   └── sweetbloom_db.sql
├── public/
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   ├── delete.php
│   └── assets/
│       └── style.css
└── README.md

## Cara menjalankan
1. Install dan buka XAMPP.
2. Jalankan Apache dan MySQL.
3. Salin folder `sweetbloom` ke `C:/xampp/htdocs/`.
4. Buka `http://localhost/phpmyadmin`.
5. Import file `database/sweetbloom_db.sql`.
6. Pastikan database `sweetbloom_db` berhasil dibuat.
7. Buka:
   http://localhost/sweetbloom/public/

## Data uji
Beberapa data buket sudah disediakan di SQL.

## Pengujian
- Nama kurang dari 3 karakter → ditolak.
- Harga 0/negatif → ditolak.
- Stok negatif → ditolak.
- Nama buket duplikat → ditolak.
- Refresh setelah tambah → tidak membuat data ganda.
- Search/filter → menggunakan GET dan prepared statement.
- Delete → menggunakan POST dan token CSRF.
