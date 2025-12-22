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
        'slug',
        'description',
        'price',
        'stock',
        'category',
        'image_url', // Keep untuk backward compatibility
        'image_path', // Path untuk uploaded file
        'age_range',
        'brand',
        'rating',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'stock' => 'integer'
    ];

    // Append image_url untuk response API
    protected $appends = ['full_image_url'];

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

    // Accessor untuk mendapatkan full URL image
    public function getFullImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        // Fallback ke image_url jika masih ada data lama
        if ($this->image_url) {
            return $this->image_url;
        }

        return null;
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
