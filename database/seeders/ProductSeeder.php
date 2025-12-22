<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'LEGO City Police Station',
            'description' => 'Set LEGO kantor polisi lengkap dengan kendaraan dan mini figures.',
            'price' => 1250000,
            'stock' => 15,
            'category' => 'lego',
            'image_url' => null, // ← UBAH JADI NULL
            'image_path' => null, // ← TAMBAHKAN INI
            'age_range' => '8-12',
            'brand' => 'LEGO',
            'rating' => 4.5,
            'is_featured' => true
        ]);

        Product::create([
            'name' => 'Gundam RX-78-2 Ver. Ka',
            'description' => 'Model kit Gundam skala 1/100 dengan detail tinggi.',
            'price' => 850000,
            'stock' => 8,
            'category' => 'action_figure',
            'image_url' => null, // ← UBAH JADI NULL
            'image_path' => null, // ← TAMBAHKAN INI
            'age_range' => '13+',
            'brand' => 'Bandai',
            'rating' => 4.8,
            'is_featured' => true
        ]);

        Product::create([
            'name' => 'Monopoly Classic',
            'description' => 'Permainan papan klasik untuk keluarga.',
            'price' => 350000,
            'stock' => 25,
            'category' => 'board_game',
            'image_url' => null, // ← UBAH JADI NULL
            'image_path' => null, // ← TAMBAHKAN INI
            'age_range' => '8-12',
            'brand' => 'Hasbro',
            'rating' => 4.3,
            'is_featured' => false
        ]);
    }
}
