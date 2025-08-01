<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Role constants
    const ROLE_PELANGGAN = 'pelanggan';
    const ROLE_APOTEKER = 'apoteker';
    const ROLE_ADMIN = 'admin';

    // Scope methods
    public function scopePelanggan($query)
    {
        return $query->where('role', self::ROLE_PELANGGAN);
    }

    public function scopeApoteker($query)
    {
        return $query->where('role', self::ROLE_APOTEKER);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }



    // Helper methods
    public function isPelanggan()
    {
        return $this->role === self::ROLE_PELANGGAN;
    }

    public function isApoteker()
    {
        return $this->role === self::ROLE_APOTEKER;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Relationships
    public function salesAsCustomer()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

    public function salesAsApoteker()
    {
        return $this->hasMany(Sale::class, 'served_by_user_id');
    }

    public function createdPurchases()
    {
        return $this->hasMany(Purchase::class, 'created_by_user_id');
    }
}
