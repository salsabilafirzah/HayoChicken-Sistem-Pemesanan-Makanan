# Hayo Chicken - Sistem Informasi E-Commerce Kuliner & Ordering

Sistem Informasi E-Commerce Kuliner & Ordering terpadu untuk **Hayo Chicken**, dirancang secara komprehensif untuk menangani seluruh siklus transaksi *end-to-end*. Mulai dari proses pemesanan oleh pelanggan hingga manajemen operasional dan ketersediaan bahan baku (BOM) oleh penjual. Sistem ini menyatukan dua lingkungan operasi khusus (Akses Pembeli & Akses Penjual) ke dalam satu ekosistem yang kohesif.

## Tech Stack & Arsitektur

Proyek ini dibangun menggunakan standar pengembangan perangkat lunak modern untuk memastikan skalabilitas dan performa:
| Komponen Arsitektur | Teknologi Utama | Deskripsi Integrasi |
| :--- | :--- | :--- |
| **Backend & API Services** | Laravel (PHP) | Mengendalikan _business logic_, *State Machine* pesanan, dan analitik BOM. |
| **Frontend / Mobile Client** | Flutter (Dart) | Antarmuka dinamis dan responsif dengan integrasi _Optimistic UI_ untuk *cart*. |
| **Database Management** | MySQL Relational Database | Basis data relasional terpusat untuk menjaga integritas transaksi. |
| **Security & Auth** | Laravel Sanctum | Pengamanan terpadu lapis-RESTful berbasis *Token-Based Authentication*. |

---

## Fitur Unggulan Sistem (Key Highlights)

Berbeda dengan proyek *boilerplate* konvensional, repositori ini memamerkan implementasi *business logic* kompleks yang siap-produksi, di antaranya:

### 1. Smart Forecasting & Analitik BOM (Bill of Materials)
Modul pintar untuk memastikan manajemen inventaris yang presisi. Sistem tidak hanya mengolah pesanan, tetapi ikut memprediksi dan memonitor ketersediaan bahan mentah berdasarkan pergerakan transaksi *real-time*. Ini mencegah *overselling* dan mitigasi kehabisan suplai bahan.

### 2. Strict State Machine Validation
Siklus hidup setiap order (contoh: `Pending` -> `Diproses` -> `Siap` -> `Selesai` atau `Dibatalkan`) dijaga dan divalidasi ke dalam pola operasional **State Machine**. Tidak ada jalan pintas yang merusak integritas *database*, mencegah anomali status (misalnya order yang sudah selesai tiba-tiba dibatalkan).

### 3. Rejection Reason Tracking
Setiap tindakan pembatalan diwajibkan untuk dijustifikasi. Penjual harus menyertakan **Alasan Penolakan** (Rejection Reason) yang akan disematkan ke dalam rekam jejak pesanan secara tepercaya. Sangat berguna untuk audit dan menjaga transparansi operasional bisnis.

### 4. Optimistic UI Cart (Bebas Lag)
Pengalaman *checkout* dibuat instan bagi para pelanggan! Memanfaatkan teknik state management **Optimistic Loading**, manipulasi _item_ pada keranjang (tambah/hapus) langsung berubah pada antarmuka pengguna secepat kilat (0-detik) tanpa *freeze*, sebelum sinkronisasi *background* dengan server selesai.

### 5. Multi-Layer Role Middleware
Setiap transaksi keuangan, keranjang belanja, hingga hak akses antarmuka diproteksi secara proaktif (_Hard-Coded Security_) lewat kombinasi autentikasi dinamis dan *Middleware Role*.

---

## Cara Instalasi (Clone Repo)

Database asli **tidak ikut di-upload** demi keamanan. Jangan khawatir! Aplikasi ini sudah dipersenjatai dengan *Migration* dan *Seeder* pintar yang akan membangun ulang struktur dan menaburkan data Dummy dengan satu baris perintah.

```bash
# 1. Clone repository ke mesin lokal
git clone https://github.com/salsabilafirzah/HayoChicken-Sistem-Pemesanan-Makanan.git

# 2. Masuk ke direktori
cd HayoChicken-Sistem-Pemesanan-Makanan

# 3. Instal semua dependency PHP
composer install

# 4. Salin file environment dan atur setelan Database Anda
cp .env.example .env
php artisan key:generate

# 5. Bangun ulang struktur Database beserta Data Dummy (SUDAH TERMASUK AKUN)
php artisan migrate --seed

# 6. Jalankan Server API
php artisan serve --host=0.0.0.0
```

> **INFO PENTING**: Jangan lupa untuk memastikan IP Address di file Flutter (`api_service.dart`) mengarah ke alamat IP server lokal (Laragon/Artisan) Anda.

---

## Kredensial Login (Testing Demo)

Berikut adalah akun yang terbentuk secara otomatis hasil dari instruksi _Seeder_ di atas. Gunakan ini untuk *login* ke dalam aplikasi ketika proses *testing* atau demonstrasi:

**1. Akses Penjual (Seller / Admin)**
- Email: **admin@hayochicken.com**
- Password: **password123**
- *Fitur: Menerima Pesanan, Membatalkan Pesanan, Menambahkan Menu, Dashboard Analitik BOM.*

**2. Akses Pembeli (Buyer)**
- Email: **budi@gmail.com**
- Password: **password123**
- *Fitur: Eksplorasi Menu, Tambah Keranjang Mutakhir (Optimistic Loading), dan Checkout.*

---

Selamat berkarya dan menikmati kelezatan kodenya!
