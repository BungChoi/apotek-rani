<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'sale_number',
        'customer_id',
        'served_by_user_id',
        'total_amount',
        'payment_method',
        'status',
        'sale_date',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    // Status constants
    const STATUS_COMPLETED = 'completed';
    const STATUS_REFUNDED = 'refunded';

    // Payment method constants
    const PAYMENT_CASH = 'cash';
    const PAYMENT_TRANSFER = 'transfer';
    const PAYMENT_CREDIT_CARD = 'credit_card';
    const PAYMENT_DEBIT_CARD = 'debit_card';
    const PAYMENT_E_WALLET = 'e_wallet';
    const PAYMENT_QRIS = 'qris';

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function servedBy()
    {
        return $this->belongsTo(User::class, 'served_by_user_id');
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    // Scope methods
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('sale_date', Carbon::today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('sale_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByApoteker($query, $apotekerId)
    {
        return $query->where('served_by_user_id', $apotekerId);
    }

    // Helper methods
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function isCashPayment()
    {
        return $this->payment_method === self::PAYMENT_CASH;
    }

    public function getPaymentMethodLabel()
    {
        $methods = [
            self::PAYMENT_CASH => 'Tunai',
            self::PAYMENT_TRANSFER => 'Transfer Bank',
            self::PAYMENT_CREDIT_CARD => 'Kartu Kredit',
            self::PAYMENT_DEBIT_CARD => 'Kartu Debit',
            self::PAYMENT_E_WALLET => 'E-Wallet',
            self::PAYMENT_QRIS => 'QRIS',
        ];
        
        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getFormattedSaleNumber()
    {
        return 'SL-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Auto-generate sale number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (!$sale->sale_number) {
                $sale->sale_number = 'SL-' . Carbon::now()->format('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            
            if (!$sale->sale_date) {
                $sale->sale_date = Carbon::now();
            }
        });
    }

    // Business logic methods
    public function calculateTotal()
    {
        $this->total_amount = $this->saleDetails->sum('total_price');
        $this->save();
    }

    public function processRefund()
    {
        $this->status = self::STATUS_REFUNDED;
        $this->save();

        // Return stock
        foreach ($this->saleDetails as $detail) {
            $detail->product->increaseStock($detail->quantity);
        }
    }

    // Static helper methods
    public static function getTodaySales()
    {
        return static::today()->sum('total_amount');
    }

    public static function getThisMonthSales()
    {
        return static::thisMonth()->sum('total_amount');
    }

    public static function getSalesByPaymentMethod()
    {
        return static::selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
                    ->groupBy('payment_method')
                    ->get();
    }
}
