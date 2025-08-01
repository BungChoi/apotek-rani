<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'supplier_id',
        'category_id',
        'stock',
        'price',
        'expired_date',
        'description',
        'image',
        'total_sold',
        'status',
    ];

    protected $casts = [
        'expired_date' => 'date',
        'price' => 'decimal:2',
    ];

    // Status constants
    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_HABIS = 'habis';
    const STATUS_KADALUARSA = 'kadaluarsa';

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    // Scope methods
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_TERSEDIA)
                    ->where('stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('status', self::STATUS_HABIS)
                    ->orWhere('stock', '<=', 0);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_KADALUARSA)
                    ->orWhere('expired_date', '<', Carbon::today());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expired_date', '<=', Carbon::today()->addDays($days))
                    ->where('expired_date', '>=', Carbon::today());
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === self::STATUS_TERSEDIA && $this->stock > 0;
    }

    public function isOutOfStock()
    {
        return $this->status === self::STATUS_HABIS || $this->stock <= 0;
    }

    public function isExpired()
    {
        return $this->status === self::STATUS_KADALUARSA || $this->expired_date < Carbon::today();
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expired_date <= Carbon::today()->addDays($days) && $this->expired_date >= Carbon::today();
    }

    public function decreaseStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            $this->total_sold += $quantity;
            
            // Update status if stock becomes 0
            if ($this->stock <= 0) {
                $this->status = self::STATUS_HABIS;
            }
            
            $this->save();
            return true;
        }
        return false;
    }

    public function increaseStock($quantity)
    {
        $this->stock += $quantity;
        
        // Update status if stock becomes available
        if ($this->stock > 0 && ($this->status === self::STATUS_HABIS || $this->status === '')) {
            // Only change to available if not expired
            if (!$this->isExpired()) {
                $this->status = self::STATUS_TERSEDIA;
            }
        }
        
        // Always call updateStatus to ensure proper status
        $this->updateStatus();
        
        $this->save();
    }

    public function updateStatus()
    {
        if ($this->isExpired()) {
            $this->status = self::STATUS_KADALUARSA;
        } elseif ($this->stock <= 0) {
            $this->status = self::STATUS_HABIS;
        } else {
            $this->status = self::STATUS_TERSEDIA;
        }
        $this->save();
    }

    // Accessor
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'Habis';
        } elseif ($this->stock <= 10) {
            return 'Stok Sedikit';
        } else {
            return 'Tersedia';
        }
    }
}
