<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * GET ALL - Menampilkan semua produk dengan pagination & filtering
     * PUBLIC - Tidak perlu authentication
     * URL: GET /api/products?page=1&limit=10&search=lego&orderBy=price&sortBy=desc
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search', '');
        $orderBy = $request->query('orderBy', 'created_at');
        $sortBy = $request->query('sortBy', 'desc');

        $query = Product::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $query->orderBy($orderBy, $sortBy);

        $products = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }

    /**
     * GET BY ID - Menampilkan detail produk berdasarkan ID
     * PUBLIC - Tidak perlu authentication
     * URL: GET /api/products/{id}
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);
    }

    /**
     * POST - Membuat produk baru
     * PROTECTED - Butuh authentication (middleware auth:api)
     * URL: POST /api/products
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|in:action_figure,lego,puzzle,board_game,educational,collector',
            'image_url' => 'nullable|url',
            'age_range' => 'required|in:0-3,4-7,8-12,13+',
            'brand' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_featured' => 'nullable|boolean',
            'file' => 'nullable|file|max:5120', // Max 5MB
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('products', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $product = Product::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * PATCH - Update produk berdasarkan ID
     * PROTECTED - Butuh authentication (middleware auth:api)
     * URL: PATCH /api/products/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category' => 'sometimes|in:action_figure,lego,puzzle,board_game,educational,collector',
            'image_url' => 'nullable|url',
            'age_range' => 'sometimes|in:0-3,4-7,8-12,13+',
            'brand' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_featured' => 'nullable|boolean',
            'file' => 'nullable|file|max:5120', // Max 5MB
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($product->file_path && Storage::disk('public')->exists($product->file_path)) {
                Storage::disk('public')->delete($product->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('products', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $product->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    /**
     * DELETE - Hapus produk berdasarkan ID (soft delete)
     * PROTECTED - Butuh authentication (middleware auth:api)
     * URL: DELETE /api/products/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        // Soft delete (tidak menghapus file karena menggunakan soft delete)
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
