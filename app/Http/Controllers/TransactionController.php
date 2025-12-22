<?php
// app/Http\Controllers\TransactionController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * GET ALL - Menampilkan semua transaksi
     * PROTECTED - Butuh authentication (middleware auth:api)
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search', '');
        $status = $request->query('status');
        $orderBy = $request->query('orderBy', 'created_at');
        $sortBy = $request->query('sortBy', 'desc');

        $query = Transaction::with(['product', 'user']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('product', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $query->orderBy($orderBy, $sortBy);
        $transactions = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions
        ], 200);
    }

    /**
     * GET BY CODE - Menampilkan transaksi berdasarkan transaction_code
     */
    public function show($code): JsonResponse
    {
        $transaction = Transaction::with(['product', 'user'])
            ->where('transaction_code', $code)
            ->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction
        ], 200);
    }

    /**
     * POST - Membuat transaksi baru dan mengurangi stok produk
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            // Ambil product
            $product = Product::find($validated['product_id']);

            // Validasi stok
            if ($product->stock < $validated['quantity']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient stock. Available: ' . $product->stock
                ], 400);
            }

            // Hitung total harga
            $totalPrice = $validated['quantity'] * $validated['unit_price'];

            // PERBAIKAN DI SINI: Gunakan Auth::guard('api')->id()
            $userId = Auth::guard('api')->id();

            if (!$userId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'product_id' => $validated['product_id'],
                'user_id' => $userId, // Pakai $userId dari Auth
                'quantity' => $validated['quantity'],
                'unit_price' => $validated['unit_price'],
                'total_price' => $totalPrice,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Kurangi stok produk (menggunakan method yang sudah dibuat)
            $product->updateStock($validated['quantity'], 'decrease');

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction created successfully and stock updated',
                'data' => $transaction->load(['product', 'user'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * PATCH - Update status transaksi
     */
    public function update(Request $request, $code): JsonResponse
    {
        $transaction = Transaction::where('transaction_code', $code)->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Jika status diubah menjadi cancelled dan sebelumnya completed, kembalikan stok
        if ($validated['status'] === 'cancelled' && $transaction->status === 'completed') {
            $transaction->product->updateStock($transaction->quantity, 'increase');
        }

        $transaction->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction updated successfully',
            'data' => $transaction->load(['product', 'user'])
        ], 200);
    }

    /**
     * DELETE - Cancel transaksi (soft delete)
     */
    public function destroy($code): JsonResponse
    {
        $transaction = Transaction::where('transaction_code', $code)->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        // Jika transaksi completed, kembalikan stok saat dihapus
        if ($transaction->status === 'completed') {
            $transaction->product->updateStock($transaction->quantity, 'increase');
        }

        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction cancelled successfully and stock restored'
        ], 200);
    }

    /**
     * GET - Transaksi oleh user yang login
     */
    public function myTransactions(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);

        // PERBAIKAN DI SINI: Gunakan Auth::guard('api')->id()
        $userId = Auth::guard('api')->id();

        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        $transactions = Transaction::with(['product'])
            ->where('user_id', $userId) // Pakai $userId dari Auth
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'My transactions retrieved successfully',
            'data' => $transactions
        ], 200);
    }
}
