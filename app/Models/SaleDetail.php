<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $table = 'sale_details';

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scope methods
    public function scopeBySale($query, $saleId)
    {
        return $query->where('sale_id', $saleId);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeToday($query)
    {
        return $query->whereHas('sale', function ($q) {
            $q->whereDate('sale_date', today());
        });
    }

    public function scopeThisMonth($query)
    {
        return $query->whereHas('sale', function ($q) {
            $q->whereBetween('sale_date', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        });
    }

    // Helper methods
    public function getFormattedUnitPrice()
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }

    public function getFormattedTotalPrice()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Auto-calculate total price
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            $detail->total_price = $detail->quantity * $detail->unit_price;
        });

        static::created(function ($detail) {
            // Decrease stock when sale detail is created
            $detail->product->decreaseStock($detail->quantity);
        });

        static::deleted(function ($detail) {
            // Return stock when sale detail is deleted (refund)
            $detail->product->increaseStock($detail->quantity);
        });
    }
}
