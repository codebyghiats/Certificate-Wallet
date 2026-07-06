# Implementation Plan: Personal Certificate Wallet (Personal Project)

Dokumen ini adalah blueprint lengkap untuk AI Builder / AI Developer guna membangun aplikasi **Personal Certificate Wallet** yang aman, ringan, gratis (memanfaatkan free-tier), dan dapat diinstal di HP sebagai PWA.

---

## 1. Project Overview & Scope

- **Nama Projek**: Personal Certificate Wallet
- **Tujuan**: Aplikasi dompet digital privat untuk mengunggah, menyimpan, dan mengelola sertifikat serta dokumen penting secara manual.
- **Prinsip Utama**:
  - Desain UI simpel, bersih, dan fokus pada perangkat mobile (_mobile-first_).
  - Dapat diinstal di HP melalui fitur Progressive Web App (PWA).
  - Tanpa fitur AI scanning (100% input manual untuk menghemat biaya).
  - Aman, privat, dan dioptimalkan untuk _free-tier cloud services_.

---

## 2. Tech Stack Architecture (100% Free-Tier Friendly)

- **Backend**: Laravel 11+ (PHP 8.2+) — Menangani autentikasi aman, Eloquent ORM, dan API.
- **Frontend**: Responsive Web App (Tailwind CSS + Blade Components atau Inertia.js dengan Vue/React).
- **PWA Engine**: Service Workers, Web App Manifest (`manifest.json`), dan Icons untuk mendukung fitur instalasi di HP Android & iOS.
- **Database**: PostgreSQL / MySQL (Hosted gratis di Supabase, Neon, atau Railway).
- **Storage**: Supabase Storage atau AWS S3 Compatible Free Tier (Untuk simpan PDF/Foto dokumen secara aman).

---

## 3. Database Schema Blueprint

### 1. Tabel `users` (Autentikasi Bawaan Laravel)

- `id` (BIGINT, Primary Key)
- `name` (VARCHAR)
- `email` (VARCHAR, Unique)
- `password` (VARCHAR)
- `timestamps`

### 2. Tabel `categories`

- `id` (BIGINT, Primary Key)
- `name` (VARCHAR) — _Contoh data: 'Identitas (KTP/SIM)', 'Sertifikasi IT', 'Akademik', 'Dokumen Lainnya'_
- `icon` (VARCHAR) — _Nama identifier untuk icon Lucide/Heroicons_
- `timestamps`

### 3. Tabel `certificates`

- `id` (BIGINT, Primary Key)
- `user_id` (FOREIGN KEY -> `users.id`, Cascade on Delete)
- `category_id` (FOREIGN KEY -> `categories.id`, Restrict on Delete)
- `title` (VARCHAR) — _Nama sertifikat/dokumen_
- `issuer` (VARCHAR, Nullable) — _Penerbit, contoh: Cisco, Dicoding, Kemendikbud_
- `file_path` (VARCHAR) — _Path file aman yang sudah di-hash_
- `file_type` (VARCHAR) — _pdf, png, jpeg_
- `expired_at` (DATE, Nullable) — _Tanggal kedaluwarsa dokumen (jika ada)_
- `notes` (TEXT, Nullable) — _Catatan seperti nomor registrasi atau link verifikasi_
- `timestamps`

---

## 4. Feature Requirements & User Flow

### Fase 1: Autentikasi & Keamanan Dasar

- [ ] **Secure Login**: Halaman login menggunakan email dan password.
- [ ] **Registrasi Terbatas**: Fitur registrasi bisa dimatikan via `.env` (`REGISTRATION_ENABLED=false`) setelah kamu membuat akun pertama, agar tidak ada orang lain yang bisa mendaftar.
- [ ] **Route Protection**: Semua halaman dokumen wajib menggunakan middleware `auth`.

### Fase 2: Manajemen Dokumen (CRUD)

- [ ] **Dashboard Overview**:
  - Menampilkan total dokumen dan dokumen yang akan segera kedaluwarsa.
  - Pencarian cepat berdasarkan judul, penerbit (_issuer_), atau catatan.
- [ ] **Form Upload Manual**:
  - Input: Judul, Kategori (Dropdown), Penerbit (Opsional), Tanggal Kedaluwarsa (Opsional), Catatan (Opsional).
  - Validasi file: Hanya menerima **PDF, PNG, JPG, JPEG** (Max size: 5MB).
- [ ] **Document Vault View**:
  - Tampilan _grid_ atau _list_ rapi yang dikelompokkan per Kategori.
  - **Inline Viewer**: Pratinjau langsung untuk PDF dan foto di dalam aplikasi (menggunakan modal) tanpa harus mengunduh file terlebih dahulu.

### Fase 3: Optimasi Keamanan File

- [ ] **File Name Obfuscation**: Setiap file yang diunggah harus diubah namanya secara otomatis menjadi string UUID acak (contoh: `certificates/8f3b9a1c-d2e4-4731-92cb.pdf`) untuk mencegah eksploitasi tebak nama file.
- [ ] **Status Kedaluwarsa**: Indikator warna pada dokumen (Kuning jika < 30 hari menuju kedaluwarsa, Merah jika sudah habis masa berlakunya).

### Fase 4: Integrasi PWA (Instalasi HP)

- [ ] **Web App Manifest**: Menyediakan file `manifest.json` yang berisi konfigurasi nama aplikasi, `start_url`, warna tema, dan tipe tampilan `standalone` (agar saat dibuka di HP tidak memunculkan bar browser).
- [ ] **Service Worker**: Script dasar untuk melakukan caching aset utama agar aplikasi terasa instan saat dibuka dari homescreen HP.
- [ ] **App Icons**: Menyediakan aset ikon berukuran standar (192x192 dan 512x512 piksel) untuk logo aplikasi di homescreen.

---

## 5. Instruksi Prompt untuk AI Builder (Cara Pakai)

Gunakan urutan prompt di bawah ini secara bertahap pada AI Builder-mu untuk hasil yang maksimal:

### **Prompt 1: Setup Projek & Database**

> "Bertindaklah sebagai Senior Fullstack Developer. Tolong siapkan projek Laravel bernama `personal-certificate-wallet`. Buat file migration, model, dan relasi antara model `User`, `Category`, dan `Certificate` sesuai dengan spesifikasi 'Database Schema Blueprint' yang diberikan. Buat juga Seeder untuk mengisi tabel `categories` dengan 4 data awal: 'Identitas', 'Sertifikasi IT', 'Akademik', dan 'Penghargaan'."

### **Prompt 2: Logika Backend & Upload File**

> "Buat sebuah `CertificateController` yang menangani fungsi CRUD lengkap (Create, Read, Update, Delete). Implementasikan logika upload file yang aman. Pastikan setiap file yang diunggah namanya diubah otomatis menggunakan `Str::uuid()` sebelum disimpan ke storage. Berikan proteksi agar user hanya bisa melihat dokumen miliknya sendiri."

### **Prompt 3: Pembuatan UI/UX Dashboard Mobile-First**

> "Buat tampilan antarmuka (UI) dashboard yang bersih, minimalis, dan modern menggunakan Tailwind CSS. Fokus pada desain mobile-first karena aplikasi ini akan diinstal di HP pengguna. UI harus mencakup: halaman ringkasan (dashboard), grid daftar dokumen per kategori, modal form upload, dan modal untuk pratinjau (preview) file gambar/PDF secara langsung tanpa download."

### **Prompt 4: Transformasi Menjadi Installable PWA**

> "Sekarang, ubah aplikasi web Laravel ini agar bisa diinstal di HP Android dan iOS sebagai PWA (Progressive Web App). Tolong buatkan file `manifest.json` di folder public dengan mode tampilan `standalone`, daftarkan service worker dasar untuk menangani siklus hidup aplikasi, dan berikan panduan di mana meletakkan ikon aplikasi (192x192 dan 512x512). Pastikan tag manifest sudah terhubung di layout HTML utama."
