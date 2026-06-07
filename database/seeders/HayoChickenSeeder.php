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
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // =====================================================================
        // SEED PRODUCTS
        // =====================================================================
        $ayamCat = Category::where('slug', 'ayam')->first();
        $paketCat = Category::where('slug', 'paket')->first();
        $cemilanCat = Category::where('slug', 'cemilan')->first();
        $minumanCat = Category::where('slug', 'minuman')->first();

        $products = [
            [
                'category_id' => $ayamCat->id,
                'name' => 'Ayam Goreng Crispy',
                'slug' => 'ayam-goreng-crispy',
                'description' => 'Ayam pilihan digoreng bumbu rahasia. Crispy di luar, juicy di dalam!',
                'base_price' => 12000,
                'image_url' => '/assets/fried_chicken.png',
                'is_available' => true,
            ],
            [
                'category_id' => $paketCat->id,
                'name' => 'Paket Nasi Ayam',
                'slug' => 'paket-nasi-ayam',
                'description' => '1 ayam crispy + nasi putih + teh manis hangat.',
                'base_price' => 18000,
                'image_url' => '/assets/paket_nasi_mie.png',
                'is_available' => true,
            ],
            [
                'category_id' => $cemilanCat->id,
                'name' => 'Kentang Goreng',
                'slug' => 'kentang-goreng',
                'description' => 'Kentang goreng renyah, tersedia saus sambal & keju.',
                'base_price' => 8000,
                'image_url' => '/assets/rice_bowl.png', // Fallback
                'is_available' => true,
            ],
            [
                'category_id' => $minumanCat->id,
                'name' => 'Es Teh Manis',
                'slug' => 'es-teh-manis',
                'description' => 'Teh manis dingin yang bikin seger sepanjang hari.',
                'base_price' => 5000,
                'image_url' => '/assets/lemon_tea.png',
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // 4. Seed Product Extras
        $product1 = Product::where('slug', 'ayam-goreng-crispy')->first();
        ProductExtra::create([
            'product_id' => $product1->id,
            'name' => 'Nasi Putih',
            'additional_price' => 4000,
            'is_available' => 1,
            'sort_order' => 1,
        ]);

        ProductExtra::create([
            'product_id' => $product1->id,
            'name' => 'Saus Ekstra',
            'additional_price' => 2000,
            'is_available' => 1,
            'sort_order' => 2,
        ]);

        // 5. Seed Raw Materials
        $rawMaterials = [
            ['name' => 'Daging Ayam Segar', 'unit' => 'kg', 'current_stock' => 8.5, 'minimum_threshold' => 5.0],
            ['name' => 'Tepung Bumbu Crispy', 'unit' => 'kg', 'current_stock' => 3.2, 'minimum_threshold' => 2.0],
            ['name' => 'Minyak Goreng', 'unit' => 'liter', 'current_stock' => 12.0, 'minimum_threshold' => 5.0],
            ['name' => 'Nasi Putih (Beras)', 'unit' => 'kg', 'current_stock' => 15.0, 'minimum_threshold' => 8.0],
        ];

        foreach ($rawMaterials as $rm) {
            RawMaterial::create($rm);
        }

        // 6. Seed BOM (Bill of Materials)
        $rmAyam = RawMaterial::where('name', 'Daging Ayam Segar')->first();
        $rmTepung = RawMaterial::where('name', 'Tepung Bumbu Crispy')->first();
        $rmMinyak = RawMaterial::where('name', 'Minyak Goreng')->first();
        $rmBeras = RawMaterial::where('name', 'Nasi Putih (Beras)')->first();

        $ayamCrispy = Product::where('slug', 'ayam-goreng-crispy')->first();
        $paketAyam = Product::where('slug', 'paket-nasi-ayam')->first();

        // Ayam Goreng Crispy needs: 0.25kg ayam, 0.05kg tepung, 0.1L minyak
        MenuBom::create(['product_id' => $ayamCrispy->id, 'raw_material_id' => $rmAyam->id, 'quantity_needed' => 0.25]);
        MenuBom::create(['product_id' => $ayamCrispy->id, 'raw_material_id' => $rmTepung->id, 'quantity_needed' => 0.05]);
        MenuBom::create(['product_id' => $ayamCrispy->id, 'raw_material_id' => $rmMinyak->id, 'quantity_needed' => 0.1]);

        // Paket Ayam needs: same + 0.15kg beras
        MenuBom::create(['product_id' => $paketAyam->id, 'raw_material_id' => $rmAyam->id, 'quantity_needed' => 0.25]);
        MenuBom::create(['product_id' => $paketAyam->id, 'raw_material_id' => $rmTepung->id, 'quantity_needed' => 0.05]);
        MenuBom::create(['product_id' => $paketAyam->id, 'raw_material_id' => $rmMinyak->id, 'quantity_needed' => 0.1]);
        MenuBom::create(['product_id' => $paketAyam->id, 'raw_material_id' => $rmBeras->id, 'quantity_needed' => 0.15]);
    }
}
