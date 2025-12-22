<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * GET ALL - Menampilkan semua produk dengan pagination & filtering
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search', '');
        $orderBy = $request->query('orderBy', 'created_at');
        $sortBy = $request->query('sortBy', 'desc');

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $query->orderBy($orderBy, $sortBy);
        $products = $query->paginate($limit);

        // ✅ PERBAIKAN: Transform semua produk
        $products->getCollection()->transform(function ($product) {
            return $this->transformProduct($product);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }

    /**
     * GET BY ID - Menampilkan detail produk
     */
    public function show(string $identifier): JsonResponse
    {
        if (is_numeric($identifier)) {
            $product = Product::find($identifier);
        } else {
            $product = Product::where('slug', $identifier)->first();
        }

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        // ✅ Transform product sebelum return
        $transformedProduct = $this->transformProduct($product);
        $transformedProduct->loadCount('transactions');

        return response()->json([
            'status' => 'success',
            'message' => 'Product retrieved successfully',
            'data' => $transformedProduct
        ], 200);
    }

    /**
     * POST - Membuat produk baru
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|in:action_figure,lego,puzzle,board_game,educational,collector',
            'age_range' => 'required|in:0-3,4-7,8-12,13+',
            'brand' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_featured' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('products', $fileName, 'public');
            $validated['image_path'] = $filePath;
        }

        $product = Product::create($validated);

        // ✅ Transform product untuk response
        $transformedProduct = $this->transformProduct($product);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $transformedProduct
        ], 201);
    }

    /**
     * PATCH - Update produk berdasarkan ID
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
            'age_range' => 'sometimes|in:0-3,4-7,8-12,13+',
            'brand' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_featured' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $file = $request->file('image');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('products', $fileName, 'public');
            $validated['image_path'] = $filePath;
        }

        $product->update($validated);

        // ✅ Refresh product dari DB dan transform
        $product->refresh();
        $transformedProduct = $this->transformProduct($product);

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $transformedProduct
        ], 200);
    }

    /**
     * DELETE - Hapus produk berdasarkan ID (soft delete)
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

        // Delete image file if exists
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        // Soft delete
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ], 200);
    }

    /**
     * ✅ PERBAIKAN UTAMA: Helper transform product
     * Ensure image_url SELALU ada dalam response
     */
    private function transformProduct($product)
    {
        // Buat array data product
        $data = $product->toArray();

        // ✅ PRIORITAS: Generate image_url yang PASTI bisa diakses
        if ($product->image_path) {
            // Gunakan url() helper Laravel untuk generate full URL
            $data['image_url'] = url('storage/' . $product->image_path);
        } else {
            // Fallback ke null jika tidak ada gambar
            $data['image_url'] = null;
        }

        // Return sebagai stdClass agar konsisten dengan Eloquent model
        return (object) $data;
    }
}
