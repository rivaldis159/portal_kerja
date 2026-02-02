# Panduan Deployment (Gratis)

Berikut adalah dua opsi terbaik untuk deploy aplikasi Laravel Anda secara gratis:

1.  **Render.com (Rekomendasi - Modern)**
    *   **Kelebihan**: Support Laravel (PHP) modern, integrasi Git otomatis, server stabil.
    *   **Kekurangan**: Database Postgres gratis valid 90 hari (bisa diperpanjang/reset), disk bersifat "Ephemeral" (file upload akan hilang saat deploy ulang, kecuali pakai storage eksternal seperti S3/Cloudinary).

2.  **InfinityFree (Alternatif - Klasik)**
    *   **Kelebihan**: Gratis selamanya, cPanel (familiar), MySQL.
    *   **Kekurangan**: Proses deploy manual (upload file zip/ftp), performa kadang lambat, limitasi inodes/file.

---

## OPSI 1: Render.com (Cara Modern via Git)

Aplikasi telah saya ubah agar kompatibel dengan **PostgreSQL** (database default gratis di Render).

### Langkah-langkah:

1.  **Push Kode ke GitHub**:
    Pastikan semua kode terbaru sudah ada di repository GitHub Anda.

2.  **Daftar Render**:
    Buka [render.com](https://render.com) dan daftar menggunakan akun GitHub Anda.

3.  **Buat Database**:
    *   Klik **New +** -> **PostgreSQL**.
    *   Name: `portal-db` (bebas).
    *   Plan: **Free**.
    *   Region: Singapore (terdekat) atau Frankfurt.
    *   Klik **Create Database**.
    *   Setelah jadi, salin **"Internal Database URL"** (nanti dipakai).

4.  **Buat Web Service**:
    *   Klik **New +** -> **Web Service**.
    *   Pilih opsi **"Build and deploy from a Git repository"**.
    *   Pilih repo `portal_kerja` Anda.
    *   **Name**: `portal-bps-dairi` (bebas).
    *   **Region**: Samakan dengan database (Singapore).
    *   **Branch**: `main`.
    *   **Runtime**: **PHP**.
    *   **Build Command**: `composer install --no-dev --optimize-autoloader`
    *   **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
        *(Catatan: Ini cara termudah. Untuk performa pro bisa pakai Docker/Nginx, tapi untuk gratisan ini cukup).*
    *   **Plan**: **Free**.

5.  **Environment Variables (PENTING)**:
    Scroll ke bawah ke bagian "Environment Variables" dan klik "Add Environment Variable":
    
    | Key | Value |
    | --- | --- |
    | `APP_KEY` | (Isi dengan hasil `php artisan key:generate` di lokal atau generate baru) |
    | `APP_ENV` | `production` |
    | `APP_DEBUG` | `true` (ubah false jika sudah stabil) |
    | `APP_URL` | (Kosongkan dulu, nanti isi setelah dapat link dari Render) |
    | `DATABASE_URL` | (Paste **Internal Database URL** dari langkah no 3) |
    | `DB_CONNECTION`| `pgsql` |

    *Tips: Render otomatis mengenali `DATABASE_URL` dan akan mengonfigurasinya untuk Laravel.*

6.  **Deploy**:
    Klik **Create Web Service**. Tunggu proses build selesai.

7.  **Migrasi Database**:
    Setelah status "Live", buka tab **Shell** di dashboard Render, lalu ketik:
    `php artisan migrate --force`

---

## OPSI 2: InfinityFree (Cara Manual via FTP)

Jika memilih cara ini, Anda tidak perlu Git, tapi harus upload file manual.

1.  **Persiapan Lokal**:
    *   Jalankan `composer install --optimize-autoloader --no-dev` di laptop.
    *   Zip folder project (kecuali folder `.git` dan `node_modules`).

2.  **Upload File**:
    *   Login ke FTP InfinityFree (gunakan FileZilla).
    *   Upload isi folder project ke folder di luar `htdocs` (misal buat folder `laravel_core`).
    *   Pindahkan isi folder `public` dari Laravel ke dalam folder `htdocs` di hosting.

3.  **Edit `index.php`**:
    *   Edit file `htdocs/index.php`.
    *   Ubah path require agar menunjuk ke folder `laravel_core`:
        ```php
        require __DIR__.'/../laravel_core/vendor/autoload.php';
        $app = require __DIR__.'/../laravel_core/bootstrap/app.php';
        ```

4.  **Database**:
    *   Buat database MySQL di panel InfinityFree.
    *   Import file SQL database lokal Anda (Export dulu dari Laragon/HeidiSQL) via phpMyAdmin InfinityFree.
    *   Sesuaikan file `.env` di hosting dengan kredensial database InfinityFree.

---

### Rekomendasi Saya:
Gunakan **Render.com** karena Anda sudah menyambungkan kode dengan GitHub. Setiap kali Anda commit & push update fitur baru, website di Render akan otomatis terupdate. Jauh lebih mudah maintenance-nya!
