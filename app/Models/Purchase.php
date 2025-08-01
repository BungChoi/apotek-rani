<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'created_by_user_id',
        'purchase_date',
        'total_amount',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_ORDERED = 'ordered';
    const STATUS_RECEIVED = 'received';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Payment method constants
    const PAYMENT_CASH = 'cash';
    const PAYMENT_TRANSFER = 'transfer';
    const PAYMENT_CREDIT = 'credit';
    const PAYMENT_CHECK = 'check';

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    // Scope methods
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentMethod($query, $paymentMethod)
    {
        return $query->where('payment_method', $paymentMethod);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('purchase_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('purchase_date', [$startDate, $endDate]);
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    // Helper methods
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isReceived()
    {
        return $this->status === self::STATUS_RECEIVED;
    }

    public function isOrdered()
    {
        return $this->status === self::STATUS_ORDERED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function getFormattedPurchaseNumber()
    {
        return $this->purchase_number ?: 'PO-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Auto-generate purchase number with better logic
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            // Set default values before saving
            if (!$purchase->purchase_number) {
                $purchase->purchase_number = static::generatePurchaseNumber();
            }
            
            // Ensure total_amount has a default value
            if (is_null($purchase->total_amount)) {
                $purchase->total_amount = 0.00;
            }
            
            // Set default status if not provided
            if (!$purchase->status) {
                $purchase->status = self::STATUS_DRAFT;
            }
        });
    }

    // Improved purchase number generation
    public static function generatePurchaseNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $prefix = 'PO-' . $date . '-';
        
        // Get the last purchase number for today
        $lastPurchase = static::where('purchase_number', 'LIKE', $prefix . '%')
            ->orderBy('purchase_number', 'desc')
            ->first();
        
        if ($lastPurchase) {
            // Extract the last number and increment
            $lastNumber = (int) substr($lastPurchase->purchase_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Business logic methods
    public function calculateTotals()
    {
        $this->total_amount = $this->purchaseDetails->sum('total_cost');
        $this->save();
        return $this->total_amount;
    }

    public function markAsReceived()
    {
        $this->status = self::STATUS_RECEIVED;
        $this->save();

        // Update product stock for received items
        foreach ($this->purchaseDetails as $detail) {
            if ($detail->quantity_received > 0) {
                $detail->product->increaseStock($detail->quantity_received);
            }
        }
        
        return $this;
    }

    public function markAsCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->save();
        
        return $this;
    }

    public function markAsCancelled($reason = null)
    {
        $this->status = self::STATUS_CANCELLED;
        if ($reason) {
            $this->notes = ($this->notes ? $this->notes . ' | ' : '') . 'Cancelled: ' . $reason;
        }
        $this->save();
        
        return $this;
    }

    // Accessor for status badge (for UI)
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_ORDERED => 'primary',
            self::STATUS_RECEIVED => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Accessor for status label (for UI)
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ORDERED => 'Dipesan',
            self::STATUS_RECEIVED => 'Sebagian Diterima',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    // Accessor for payment method label (for UI)
    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            self::PAYMENT_CASH => 'Tunai',
            self::PAYMENT_TRANSFER => 'Transfer',
            self::PAYMENT_CREDIT => 'Kredit',
            self::PAYMENT_CHECK => 'Cek',
        ];

        return $labels[$this->payment_method] ?? 'Unknown';
    }

    // Get total items ordered
    public function getTotalItemsOrderedAttribute()
    {
        return $this->purchaseDetails->sum('quantity_ordered');
    }

    // Get total items received
    public function getTotalItemsReceivedAttribute()
    {
        return $this->purchaseDetails->sum('quantity_received');
    }

    // Check if all items are fully received
    public function isFullyReceived()
    {
        return $this->purchaseDetails->every(function ($detail) {
            return $detail->quantity_received >= $detail->quantity_ordered;
        });
    }

    // Check if any items are received
    public function hasAnyItemsReceived()
    {
        return $this->purchaseDetails->some(function ($detail) {
            return $detail->quantity_received > 0;
        });
    }

    // Get completion percentage
    public function getCompletionPercentageAttribute()
    {
        $totalOrdered = $this->total_items_ordered;
        $totalReceived = $this->total_items_received;
        
        if ($totalOrdered == 0) {
            return 0;
        }
        
        return round(($totalReceived / $totalOrdered) * 100, 1);
    }

    // Format total amount for display
    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    // Check if purchase can be edited
    public function canBeEdited()
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_ORDERED]);
    }

    // Check if purchase can be cancelled
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_ORDERED]);
    }

    // Check if purchase can receive items
    public function canReceiveItems()
    {
        return in_array($this->status, [self::STATUS_ORDERED, self::STATUS_RECEIVED]);
    }

    // Static methods for getting options (useful for forms)
    public static function getStatusOptions()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ORDERED => 'Dipesan',
            self::STATUS_RECEIVED => 'Sebagian Diterima',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public static function getPaymentMethodOptions()
    {
        return [
            self::PAYMENT_CASH => 'Tunai',
            self::PAYMENT_TRANSFER => 'Transfer',
            self::PAYMENT_CREDIT => 'Kredit',
            self::PAYMENT_CHECK => 'Cek',
        ];
    }
}