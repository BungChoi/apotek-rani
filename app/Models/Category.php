<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scope methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithProductCount($query)
    {
        return $query->withCount('products');
    }

    public function scopeWithAvailableProductCount($query)
    {
        return $query->withCount(['products' => function ($query) {
            $query->where('status', 'tersedia')->where('stock', '>', 0);
        }]);
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Helper methods
    public function getTotalProducts()
    {
        return $this->products()->count();
    }

    public function getAvailableProducts()
    {
        return $this->products()->where('status', 'tersedia')->where('stock', '>', 0)->count();
    }

    public function hasProducts()
    {
        return $this->products()->exists();
    }
}
