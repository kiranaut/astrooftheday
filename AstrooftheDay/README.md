# Astro of the Day

Sistem Informasi Manajemen Data sederhana untuk mengelola katalog **NASA Astronomy Picture of the Day (APOD)**.
Dibuat dengan PHP murni (tanpa framework) dan MySQL/MariaDB.

## Fitur

| Modul | Penerapan |
|---|---|
| Form & validasi | Form HTML metode POST, validasi `if-else` dan `switch`, sanitasi `filter_var()`, `strip_tags()`, dan `htmlspecialchars()` |
| CRUD | Create (`add.php`), Read (`data.php`, `detail.php`, `gallery.php`), Update (`edit.php`), Delete (`delete.php`) |
| Database | `mysqli` dengan prepared statement, koneksi di `astrooftheday/config/db.php` |
| Session | Login berbasis `$_SESSION`, semua halaman CRUD diproteksi `includes/auth.php` |
| Dual bahasa | Indonesia / Inggris, disimpan di `$_SESSION["lang"]`, ganti lewat `languange.php?lang=id` atau `en` |
| Tambahan | Impor CSV, pencarian dan paginasi, token CSRF pada form dan hapus |

## Cara menjalankan

1. Letakkan folder ini di `htdocs` (XAMPP) atau `www` (Laragon/WAMP), mis. `htdocs/AstrooftheDay`.
2. Aktifkan Apache dan MySQL.
3. Impor `database.sql` melalui phpMyAdmin (tab **Import**) atau `mysql -u root < database.sql`.
   File ini sudah membuat database `astrooftheday`, tabel, akun admin, dan 30 data awal.
4. Sesuaikan kredensial di `astrooftheday/config/db.php` bila perlu (bawaan: `root` tanpa password).
5. Buka `http://localhost/AstrooftheDay/`.

**Akun login:** `admin` / `admin123`

Persyaratan: PHP 7.4+ (disarankan 8.x) dengan ekstensi `mysqli` dan `mysqlnd`.

## Struktur berkas

```
AstrooftheDay/
|-- index.php            pengarah ke login/dashboard
|-- login.php            form login
|-- proses_post.php      memproses login (membuat session)
|-- logout.php           menghapus session
|-- languange.php        pengganti bahasa (ID/EN)
|-- dashboard.php        ringkasan data
|-- data.php             tabel data (Read) + pencarian + paginasi
|-- detail.php           detail satu data (Read)
|-- add.php              tambah data (Create)
|-- edit.php             ubah data (Update)
|-- delete.php           hapus data (Delete)
|-- gallery.php          tampilan galeri
|-- import.php           impor dataset CSV
|-- database.sql         skema + data awal
`-- astrooftheday/
    |-- config/db.php           koneksi database
    |-- includes/auth.php       penjaga session
    |-- includes/languange.php  kamus bahasa + fungsi t()
    |-- includes/helpers.php    sanitasi, validasi, CSRF, flash message
    |-- includes/header.php     layout atas + menu + pilihan bahasa
    |-- includes/footer.php     layout bawah
    |-- includes/form_apod.php  form bersama untuk add/edit
    `-- assets/css/style.css
```

## Catatan

- Gambar ditampilkan dari URL NASA (`apod.nasa.gov`), sehingga membutuhkan koneksi internet.
- Password disimpan sebagai hash dan diperiksa dengan `password_verify()`.
