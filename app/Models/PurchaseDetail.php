<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
        'total_cost',
        'product_name_snapshot',
        'expiry_date',
        'batch_number',
        'received_date',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'received_date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    // Relationships
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scope methods
    public function scopeReceived($query)
    {
        return $query->where('quantity_received', '>', 0);
    }

    public function scopePending($query)
    {
        return $query->where('quantity_received', 0);
    }

    public function scopePartiallyReceived($query)
    {
        return $query->whereColumn('quantity_received', '<', 'quantity_ordered')
                    ->where('quantity_received', '>', 0);
    }

    public function scopeFullyReceived($query)
    {
        return $query->whereColumn('quantity_received', '=', 'quantity_ordered');
    }

    // Helper methods
    public function isFullyReceived()
    {
        return $this->quantity_received >= $this->quantity_ordered;
    }

    public function isPartiallyReceived()
    {
        return $this->quantity_received > 0 && $this->quantity_received < $this->quantity_ordered;
    }

    public function isPending()
    {
        return $this->quantity_received == 0;
    }

    public function getRemainingQuantity()
    {
        return $this->quantity_ordered - $this->quantity_received;
    }

    public function getReceivalPercentage()
    {
        if ($this->quantity_ordered == 0) return 0;
        return round(($this->quantity_received / $this->quantity_ordered) * 100, 2);
    }

    // Business logic
    public function receiveQuantity($quantity, $batchNumber = null, $expiryDate = null)
    {
        $maxReceivable = $this->quantity_ordered - $this->quantity_received;
        $actualQuantity = min($quantity, $maxReceivable);

        $this->quantity_received += $actualQuantity;
        $this->received_date = Carbon::today();
        
        if ($batchNumber) {
            $this->batch_number = $batchNumber;
        }
        
        if ($expiryDate) {
            $this->expiry_date = $expiryDate;
        }

        $this->save();

        // Update product stock
        $this->product->increaseStock($actualQuantity);

        return $actualQuantity;
    }

    // Auto-calculate total cost
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            $detail->total_cost = $detail->quantity_ordered * $detail->unit_cost;
            
            // Save product name snapshot if not set
            if (!$detail->product_name_snapshot && $detail->product) {
                $detail->product_name_snapshot = $detail->product->name;
            }
        });
    }
}
