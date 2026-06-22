# 🍗 Hayo Chicken App

Aplikasi Point of Sale (POS) & Ordering kekinian untuk **Hayo Chicken**! Memiliki fitur _Seamless Checkout_, _Optimistic Cart Update_, _Real-time Promo_, dan dua buah dashboard khusus (Pembeli & Penjual) dalam satu ekosistem yang solid. 

Dibangun dengan stack **Laravel (Backend/API)** dan **Flutter (Mobile App)**.

---

## ⚡ Cara Instalasi (Clone Repo)

Database asli **tidak ikut di-upload** demi keamanan. Jangan khawatir! Aplikasi ini sudah dipersenjatai dengan *Migration* dan *Seeder* pintar yang akan membangun ulang struktur dan menaburkan data Dummy dengan satu baris perintah.

```bash
# 1. Clone repository ke mesin lokal
git clone https://github.com/salsabilafirzah/HayoChicken-Sistem-Pemesanan-Makanan.git

# 2. Masuk ke direktori
cd HayoChicken-Sistem-Pemesanan-Makanan

# 3. Instal semua dependency PHP
composer install

# 4. Salin file environment dan atur Database Anda
cp .env.example .env
php artisan key:generate

# 5. Bangun ulang struktur Database beserta Data Dummy (SUDAH TERMASUK AKUN)
php artisan migrate --seed

# 6. Jalankan Server API
php artisan serve --host=0.0.0.0
```

> **INFO PENTING**: Jangan lupa untuk memastikan IP Address di file Flutter (`api_service.dart`) mengarah ke alamat IP server lokal (Laragon/Artisan) Anda.

---

## 🔑 Kredensial Login (Testing Demo)

Berikut adalah akun yang terbentuk secara otomatis hasil dari instruksi _Seeder_ di atas. Gunakan ini untuk login ke aplikasi saat demonstrasi Dosen:

**1. 👨‍🍳 Akses Penjual (Seller / Admin)**
- Email: **admin@hayochicken.com**
- Password: **password123**
- *Fitur: Menerima Pesanan, Membatalkan Pesanan, Menambahkan Menu, Dashboard Analitik.*

**2. 🍔 Akses Pembeli (Buyer)**
- Email: **budi@gmail.com**
- Password: **password123**
- *Fitur: Eksplorasi Menu, Klaim Promo Jumat Secara Instan, Tambah Keranjang Mutakhir (Optimistic Loading), dan Checkout.*

---

## ✨ Fitur Unggulan Sistem
- **Optimistic UI Cart**: Menghapus item keranjang nol-detik tanpa membebani otak server (anti *lag*).
- **Hard-Coded Security**: Jalur transaksi dan riwayat catatan yang diproteksi *Middleware Role*.
- **Live Rejection & Notes**: Logika pencatatan obrolan mutakhir di mana Penjual dan Pembeli bisa saling meninggalkan "Catatan Transaksi".

Selamat berkarya dan menikmati kelezatan kodenya! 🚀
