<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug', // Tambah slug
        'description',
        'price',
        'stock',
        'category',
        'image_url',
        'age_range',
        'brand',
        'rating',
        'is_featured',
        'file_path',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'stock' => 'integer'
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = $product->slug ?? Str::slug($product->name) . '-' . Str::random(6);
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(6);
            }
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Method untuk update stok
    public function updateStock($quantity, $type = 'decrease')
    {
        if ($type === 'decrease') {
            if ($this->stock < $quantity) {
                throw new \Exception('Insufficient stock');
            }
            $this->stock -= $quantity;
        } else {
            $this->stock += $quantity;
        }

        $this->save();
        return $this;
    }

    // Scope untuk find by slug
    public function scopeFindBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->first();
    }
}
