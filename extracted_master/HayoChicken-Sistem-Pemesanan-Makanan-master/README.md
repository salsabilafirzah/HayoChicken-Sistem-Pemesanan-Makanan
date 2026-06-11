# Hayo Chicken - Laravel Application

Aplikasi pemesanan makanan Hayo Chicken berbasis Laravel.

## Struktur Proyek

```
HayoChicken/
├── app/
│   └── Http/
│       └── Controllers/
│           ├── AuthController.php      # Login, Register, Reset/Ubah Password, Logout
│           ├── BuyerController.php     # Semua halaman pembeli
│           └── SellerController.php    # Dashboard penjual
│
├── resources/views/
│   ├── welcome.blade.php               # Halaman splash (Daftar / Masuk)
│   ├── auth/
│   │   ├── login.blade.php             # Halaman masuk akun
│   │   ├── register.blade.php          # Halaman daftar akun
│   │   ├── reset-password.blade.php    # Lupa password
│   │   └── change-password.blade.php   # Ubah password
│   ├── buyer/
│   │   ├── home.blade.php              # Beranda pembeli
│   │   ├── product-detail.blade.php    # Detail produk
│   │   ├── cart.blade.php              # Keranjang belanja
│   │   ├── checkout.blade.php          # Halaman checkout
│   │   ├── order-success.blade.php     # Pesanan berhasil
│   │   ├── order-status.blade.php      # Status pesanan
│   │   ├── order-history.blade.php     # Riwayat pesanan
│   │   ├── order-active.blade.php      # Pesanan aktif
│   │   ├── notifications.blade.php     # Notifikasi
│   │   ├── address-saved.blade.php     # Alamat tersimpan
│   │   └── address-add.blade.php       # Tambah alamat
│   └── seller/
│       └── dashboard.blade.php         # Panel penjual
│
├── routes/
│   └── web.php                         # Semua route aplikasi
│
└── public/
    ├── css/
    │   └── app.css                     # Stylesheet utama (index/splash)
    ├── assets/                         # Gambar produk
    │   ├── fried_chicken.png
    │   ├── ayam_geprek.png
    │   ├── rice_bowl.png
    │   ├── mie-ayam.png
    │   ├── lemon_tea.png
    │   ├── mie_jebew.png
    │   ├── cemilan-pastel.png
    │   ├── three_lemon_teas.png
    │   ├── paket_combo.png
    │   └── paket_nasi_mie.png
    ├── logo_hayo.png                   # Logo utama
    └── logohayo.png                    # Logo alternatif
```

## Route Map

| URL | Route Name | View |
|-----|-----------|------|
| `/` | `welcome` | welcome.blade.php |
| `/masuk` | `login` | auth/login.blade.php |
| `/daftar` | `register` | auth/register.blade.php |
| `/lupa-password` | `password.reset` | auth/reset-password.blade.php |
| `/ubah-password` | `password.change` | auth/change-password.blade.php |
| `/beranda` | `home` | buyer/home.blade.php |
| `/produk/{id?}` | `product.detail` | buyer/product-detail.blade.php |
| `/keranjang` | `cart` | buyer/cart.blade.php |
| `/checkout` | `checkout` | buyer/checkout.blade.php |
| `/pesanan/berhasil` | `order.success` | buyer/order-success.blade.php |
| `/pesanan/status` | `order.status` | buyer/order-status.blade.php |
| `/pesanan/riwayat` | `order.history` | buyer/order-history.blade.php |
| `/pesanan/aktif` | `order.active` | buyer/order-active.blade.php |
| `/notifikasi` | `notifications` | buyer/notifications.blade.php |
| `/alamat` | `address.saved` | buyer/address-saved.blade.php |
| `/alamat/tambah` | `address.add` | buyer/address-add.blade.php |
| `/seller/dashboard` | `seller.dashboard` | seller/dashboard.blade.php |

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

## Akun Demo

- **Buyer**: email apapun + password apapun
- **Seller**: `owner@hayochicken.com` / `123`
