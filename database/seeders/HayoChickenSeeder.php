<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductExtra;
use App\Models\RawMaterial;
use App\Models\MenuBom;
use Illuminate\Support\Facades\Hash;

class HayoChickenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Users
        User::create([
            'name' => 'Admin Hayo Chicken',
            'email' => 'admin@hayochicken.com',
            'phone' => '081234567890',
            'password_hash' => Hash::make('password123'),
            'role' => 'SELLER',
            'is_email_verified' => 1,
        ]);

        User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@gmail.com',
            'phone' => '081234567891',
            'password_hash' => Hash::make('password123'),
            'role' => 'CUSTOMER',
            'is_email_verified' => 1,
        ]);

        // 2. Seed Categories
        $categories = [
            ['name' => 'Ayam', 'slug' => 'ayam', 'icon_name' => 'fried_chicken', 'sort_order' => 1],
            ['name' => 'Paket', 'slug' => 'paket', 'icon_name' => 'paket_combo', 'sort_order' => 2],
            ['name' => 'Cemilan', 'slug' => 'cemilan', 'icon_name' => 'cemilan-pastel', 'sort_order' => 3],
            ['name' => 'Minuman', 'slug' => 'minuman', 'icon_name' => 'lemon_tea', 'sort_order' => 4],
            ['name' => 'Mie', 'slug' => 'mie', 'icon_name' => 'mie_jebew', 'sort_order' => 5],
        ];

        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat['slug']] = Category::create($cat)->id;
        }

        // =====================================================================
        // SEED PRODUCTS
        // =====================================================================
        $products = [
            // 1. Paket
            ['cat' => 'paket', 'slug' => 'paket-ayam-geprek', 'name' => 'Paket Ayam Geprek', 'desc' => 'Ayam geprek pedas + Nasi Putih + Es Teh Manis', 'price' => 16000, 'img' => '/assets/paket_geprek.png', 'variants' => ['Sayap', 'Paha', 'Dada']],
            ['cat' => 'paket', 'slug' => 'paket-nasgor', 'name' => 'Paket Nasi Goreng', 'desc' => 'Nasi goreng gurih lengkap dengan kerupuk', 'price' => 17000, 'img' => '/assets/paket_nasgor.png'],
            ['cat' => 'paket', 'slug' => 'paket-katsu', 'name' => 'Paket Chicken Katsu', 'desc' => 'Chicken katsu dengan saus spesial + Nasi Putih', 'price' => 19000, 'img' => '/assets/paket_katsu.png'],
            ['cat' => 'paket', 'slug' => 'paket-teriyaki', 'name' => 'Paket Teriyaki', 'desc' => 'Chicken teriyaki khas Jepang + Nasi', 'price' => 16000, 'img' => '/assets/paket_teriyaki.png'],
            ['cat' => 'paket', 'slug' => 'paket-ayam-lalapan', 'name' => 'Paket Ayam Lalapan', 'desc' => 'Ayam goreng tradisional + Sambal + Nasi', 'price' => 16000, 'img' => '/assets/paket_lalapan.png', 'variants' => ['Sayap', 'Paha', 'Dada']],

            // 2. Cemilan
            ['cat' => 'cemilan', 'slug' => 'kentang-goreng', 'name' => 'Kentang Goreng', 'desc' => 'Kentang goreng renyah berkualitas', 'price' => 11000, 'img' => '/assets/kentang.png'],
            ['cat' => 'cemilan', 'slug' => 'nugget', 'name' => 'Nugget', 'desc' => 'Nugget ayam premium digoreng kering', 'price' => 11000, 'img' => '/assets/nugget.png'],
            ['cat' => 'cemilan', 'slug' => 'sosis', 'name' => 'Sosis', 'desc' => 'Sosis bakar/goreng dengan saus pilihan', 'price' => 11000, 'img' => '/assets/sosis.png'],
            ['cat' => 'cemilan', 'slug' => 'cireng', 'name' => 'Cireng', 'desc' => 'Cireng isi kenyal gurih bumbu pedas', 'price' => 11000, 'img' => '/assets/cireng.png'],
            ['cat' => 'cemilan', 'slug' => 'otak-otak', 'name' => 'Otak-otak', 'desc' => 'Otak-otak ikan digoreng garing pinggir jalan', 'price' => 11000, 'img' => '/assets/otak_otak.png'],

            // 3. Minuman
            ['cat' => 'minuman', 'slug' => 'es-teh-manis', 'name' => 'Es Teh Manis', 'desc' => 'Teh kental lokal, manis dan dingin', 'price' => 3000, 'img' => '/assets/esteh.png'],
            ['cat' => 'minuman', 'slug' => 'es-teh-lemon', 'name' => 'Es Teh Lemon', 'desc' => 'Perpaduan teh dan perasan lemon segar', 'price' => 5000, 'img' => '/assets/eslemon.png'],
            ['cat' => 'minuman', 'slug' => 'jus-mangga', 'name' => 'Jus Mangga', 'desc' => 'Jus mangga asli tebal serat vitamin C', 'price' => 9000, 'img' => '/assets/jus_mangga.png'],
            ['cat' => 'minuman', 'slug' => 'es-jeruk', 'name' => 'Es Jeruk', 'desc' => 'Es jeruk peras penghilang dahaga', 'price' => 6000, 'img' => '/assets/esjeruk.png'],
            ['cat' => 'minuman', 'slug' => 'teh-hangat', 'name' => 'Teh Hangat', 'desc' => 'Teh panas untuk menghangatkan malammu', 'price' => 2000, 'img' => '/assets/teh_hangat.png'],

            // 4. Ayam
            ['cat' => 'ayam', 'slug' => 'ayam-geprek', 'name' => 'Ayam Geprek', 'desc' => 'Ayam pedas dihancurkan dengan ulekan meresap', 'price' => 8000, 'img' => '/assets/ayam_geprek.png', 'variants' => ['Sayap', 'Paha', 'Dada']],
            ['cat' => 'ayam', 'slug' => 'ayam-krispi', 'name' => 'Ayam Krispi', 'desc' => 'Ayam original berlapis tepung renyah', 'price' => 7000, 'img' => '/assets/ayam_krispi.png', 'variants' => ['Sayap', 'Paha', 'Dada']],
            ['cat' => 'ayam', 'slug' => 'ayam-katsu', 'name' => 'Ayam Katsu', 'desc' => 'Fillet dada ayam goreng baluran tepung katsu', 'price' => 9000, 'img' => '/assets/ayam_katsu.png'],
            ['cat' => 'ayam', 'slug' => 'ayam-lalapan', 'name' => 'Ayam Lalapan', 'desc' => 'Ayam goreng kaya rempah khas tradisional', 'price' => 7000, 'img' => '/assets/ayam_lalapan.png'],

            // 5. Mie
            ['cat' => 'mie', 'slug' => 'mie-kuah', 'name' => 'Mie Kuah', 'desc' => 'Mie kuah kaldu hangat segar plus telur', 'price' => 13000, 'img' => '/assets/mie_kuah.png'],
            ['cat' => 'mie', 'slug' => 'mie-goreng', 'name' => 'Mie Goreng', 'desc' => 'Mie goreng bumbu kecap spesial komplit', 'price' => 14000, 'img' => '/assets/mie_goreng.png'],
            ['cat' => 'mie', 'slug' => 'mie-jebew', 'name' => 'Mie Jebew', 'desc' => 'Mie pedas viral dengan topping saus lumer', 'price' => 16000, 'img' => '/assets/mie_jebew.png'],
        ];

        foreach ($products as $p) {
            $created = Product::create([
                'category_id' => $catMap[$p['cat']],
                'name' => $p['name'],
                'slug' => $p['slug'],
                'description' => $p['desc'],
                'base_price' => $p['price'],
                'image_url' => $p['img'],
                'is_available' => true,
            ]);

            $sortO = 1;
            // Add forced variants first
            if (isset($p['variants'])) {
                foreach ($p['variants'] as $v) {
                    ProductExtra::create(['product_id' => $created->id, 'name' => "Bagian: $v", 'additional_price' => 0, 'is_available' => 1, 'sort_order' => $sortO++]);
                }
            }

            // Universal Extras based on category
            if ($p['cat'] === 'paket' || $p['cat'] === 'ayam' || $p['cat'] === 'mie') {
                ProductExtra::create(['product_id' => $created->id, 'name' => 'Ekstra Nasi Putih', 'additional_price' => 4500, 'is_available' => 1, 'sort_order' => $sortO++]);
                ProductExtra::create(['product_id' => $created->id, 'name' => 'Telur Ceplok', 'additional_price' => 4000, 'is_available' => 1, 'sort_order' => $sortO++]);
                ProductExtra::create(['product_id' => $created->id, 'name' => 'Saus Tambahan', 'additional_price' => 2000, 'is_available' => 1, 'sort_order' => $sortO++]);
            }
            if ($p['cat'] === 'cemilan') {
                ProductExtra::create(['product_id' => $created->id, 'name' => 'Saus Keju Ekstra', 'additional_price' => 3000, 'is_available' => 1, 'sort_order' => $sortO++]);
                ProductExtra::create(['product_id' => $created->id, 'name' => 'Saus Sambal Ekstra', 'additional_price' => 1500, 'is_available' => 1, 'sort_order' => $sortO++]);
            }
        }
    }
}
