<?php
// database/seeders/TransactionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada produk terlebih dahulu
        if (Product::count() === 0) {
            $this->call(ProductSeeder::class);
        }

        // Pastikan ada user terlebih dahulu
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@childhood.com',
                'password' => bcrypt('password123'),
            ]);
            User::factory()->create([
                'name' => 'Test Customer',
                'email' => 'customer@example.com',
                'password' => bcrypt('password123'),
            ]);
        }

        $products = Product::all();
        $users = User::all();
        $statuses = ['pending', 'completed', 'cancelled'];

        for ($i = 0; $i < 20; $i++) {
            $product = $products->random();
            $user = $users->random();
            $quantity = rand(1, 3); // Batasi quantity untuk jaga stok
            $unitPrice = $product->price;
            $totalPrice = $quantity * $unitPrice;
            $status = $statuses[array_rand($statuses)];

            // Generate transaction code
            $transactionCode = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode, // TAMBAHKAN INI
                'product_id' => $product->id,
                'user_id' => $user->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'status' => $status,
                'notes' => 'Test transaction ' . ($i + 1),
            ]);

            // Update stock jika transaksi completed
            if ($status === 'completed') {
                // Pastikan stok cukup
                if ($product->stock >= $quantity) {
                    $product->stock -= $quantity;
                    $product->save();
                } else {
                    // Jika stok tidak cukup, ubah status ke cancelled
                    $transaction->update(['status' => 'cancelled']);
                }
            }
        }

        $this->command->info('20 transactions seeded successfully!');
    }
}
