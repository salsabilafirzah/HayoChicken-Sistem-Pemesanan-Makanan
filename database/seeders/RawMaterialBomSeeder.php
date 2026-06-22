<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RawMaterial;
use App\Models\MenuBom;
use App\Models\Product;

class RawMaterialBomSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // 1. SEED ALL UNIQUE RAW MATERIALS
        // unit: pcs = satuan, gr = gram (perkiraan logis untuk resep)
        // =====================================================================
        $rawMaterials = [
            // Protein
            ['name' => 'Ayam',              'unit' => 'porsi'],
            ['name' => 'Dada Ayam',         'unit' => 'porsi'],
            ['name' => 'Telur',             'unit' => 'butir'],
            ['name' => 'Nugget',            'unit' => 'pcs'],
            ['name' => 'Sosis',             'unit' => 'pcs'],
            ['name' => 'Otak-otak',         'unit' => 'pcs'],

            // Karbohidrat
            ['name' => 'Nasi',              'unit' => 'porsi'],
            ['name' => 'Mie',               'unit' => 'porsi'],
            ['name' => 'Kentang',           'unit' => 'gr'],
            ['name' => 'Cireng',            'unit' => 'pcs'],

            // Tepung & Pelapis
            ['name' => 'Tepung Bumbu',      'unit' => 'gr'],
            ['name' => 'Tepung Terigu',     'unit' => 'gr'],
            ['name' => 'Tepung Panir',      'unit' => 'gr'],

            // Sayuran & Bumbu Segar
            ['name' => 'Cabai',             'unit' => 'gr'],
            ['name' => 'Bawang Putih',      'unit' => 'gr'],
            ['name' => 'Bawang Merah',      'unit' => 'gr'],
            ['name' => 'Bawang Bombay',     'unit' => 'pcs'],
            ['name' => 'Sawi',              'unit' => 'gr'],
            ['name' => 'Tomat',             'unit' => 'pcs'],
            ['name' => 'Timun',             'unit' => 'pcs'],
            ['name' => 'Kemangi',           'unit' => 'gr'],

            // Saus & Bumbu Cair
            ['name' => 'Minyak Goreng',     'unit' => 'ml'],
            ['name' => 'Kecap Manis',       'unit' => 'ml'],
            ['name' => 'Saus Tiram',        'unit' => 'ml'],
            ['name' => 'Saus Katsu',        'unit' => 'ml'],
            ['name' => 'Saus Teriyaki',     'unit' => 'ml'],
            ['name' => 'Saus Sambal',       'unit' => 'ml'],
            ['name' => 'Saus Kacang',       'unit' => 'ml'],
            ['name' => 'Bumbu Rujak',       'unit' => 'gr'],
            ['name' => 'Terasi',            'unit' => 'gr'],
            ['name' => 'Garam',             'unit' => 'gr'],
            ['name' => 'Merica',            'unit' => 'gr'],

            // Minuman
            ['name' => 'Teh',               'unit' => 'sachet'],
            ['name' => 'Gula',              'unit' => 'gr'],
            ['name' => 'Es Batu',           'unit' => 'pcs'],
            ['name' => 'Lemon',             'unit' => 'pcs'],
            ['name' => 'Jeruk',             'unit' => 'pcs'],
            ['name' => 'Mangga',            'unit' => 'pcs'],
            ['name' => 'Air',               'unit' => 'ml'],
        ];

        $matMap = [];
        foreach ($rawMaterials as $rm) {
            $created = RawMaterial::firstOrCreate(
                ['name' => $rm['name']],
                ['unit' => $rm['unit'], 'current_stock' => 0, 'minimum_threshold' => 10]
            );
            $matMap[$rm['name']] = $created->id;
        }

        // =====================================================================
        // 2. DEFINE BILL OF MATERIALS PER MENU
        // Format: 'slug-produk' => ['Nama Bahan', 'Nama Bahan 2', ...]
        // =====================================================================
        $menuBom = [
            // --- PAKET ---
            'paket-ayam-geprek' => ['Ayam', 'Tepung Bumbu', 'Minyak Goreng', 'Cabai', 'Bawang Putih', 'Nasi', 'Teh', 'Gula', 'Es Batu'],
            'paket-nasgor'      => ['Nasi', 'Telur', 'Bawang Merah', 'Bawang Putih', 'Kecap Manis', 'Saus Tiram', 'Garam', 'Minyak Goreng', 'Teh', 'Gula', 'Es Batu'],
            'paket-katsu'       => ['Dada Ayam', 'Tepung Terigu', 'Tepung Panir', 'Telur', 'Minyak Goreng', 'Saus Katsu', 'Nasi', 'Teh', 'Gula', 'Es Batu'],
            'paket-teriyaki'    => ['Dada Ayam', 'Saus Teriyaki', 'Bawang Bombay', 'Bawang Putih', 'Kecap Manis', 'Minyak Goreng', 'Nasi', 'Teh', 'Gula', 'Es Batu'],
            'paket-ayam-lalapan'=> ['Ayam', 'Minyak Goreng', 'Nasi', 'Cabai', 'Tomat', 'Terasi', 'Timun', 'Kemangi', 'Teh', 'Gula', 'Es Batu'],

            // --- CEMILAN ---
            'kentang-goreng'    => ['Kentang', 'Minyak Goreng', 'Garam', 'Saus Sambal'],
            'nugget'            => ['Nugget', 'Minyak Goreng', 'Saus Sambal'],
            'sosis'             => ['Sosis', 'Minyak Goreng', 'Saus Sambal'],
            'cireng'            => ['Cireng', 'Minyak Goreng', 'Bumbu Rujak'],
            'otak-otak'         => ['Otak-otak', 'Minyak Goreng', 'Saus Kacang', 'Saus Sambal'],

            // --- MINUMAN ---
            'es-teh-manis'      => ['Teh', 'Gula', 'Es Batu'],
            'es-teh-lemon'      => ['Teh', 'Lemon', 'Gula', 'Es Batu'],
            'jus-mangga'        => ['Mangga', 'Gula', 'Es Batu'],
            'es-jeruk'          => ['Jeruk', 'Gula', 'Air', 'Es Batu'],
            'teh-hangat'        => ['Teh', 'Gula'],

            // --- AYAM ---
            'ayam-geprek'       => ['Ayam', 'Tepung Bumbu', 'Minyak Goreng', 'Cabai', 'Bawang Putih'],
            'ayam-krispi'       => ['Ayam', 'Tepung Bumbu', 'Minyak Goreng'],
            'ayam-katsu'        => ['Dada Ayam', 'Tepung Terigu', 'Tepung Panir', 'Telur', 'Minyak Goreng', 'Saus Katsu'],
            'ayam-lalapan'      => ['Ayam', 'Minyak Goreng', 'Cabai', 'Tomat', 'Terasi', 'Timun', 'Kemangi'],

            // --- MIE ---
            'mie-kuah'          => ['Mie', 'Telur', 'Sawi', 'Bawang Putih', 'Garam', 'Merica'],
            'mie-goreng'        => ['Mie', 'Telur', 'Sawi', 'Bawang Putih', 'Kecap Manis', 'Minyak Goreng'],
            'mie-jebew'         => ['Mie', 'Cabai', 'Telur', 'Sosis', 'Bawang Putih', 'Kecap Manis', 'Minyak Goreng'],
        ];

        // =====================================================================
        // 3. INSERT MENU BOM LINKING product_id <-> raw_material_id
        // =====================================================================
        foreach ($menuBom as $slug => $ingredients) {
            $product = Product::where('slug', $slug)->first();
            if (!$product) {
                continue;
            }

            // Remove old BOM entries for idempotency
            MenuBom::where('product_id', $product->id)->delete();

            foreach ($ingredients as $ingredientName) {
                if (!isset($matMap[$ingredientName])) {
                    continue;
                }
                MenuBom::create([
                    'product_id'       => $product->id,
                    'raw_material_id'  => $matMap[$ingredientName],
                    'quantity_needed'  => 1, // 1 unit per porsi
                ]);
            }
        }
    }
}
