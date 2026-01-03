<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR (BIAR $order->total TETEP BISA DIPAKAI)
    |--------------------------------------------------------------------------
    */
    public function getTotalAttribute()
    {
        return $this->total_price;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS (BIAR VIEW BERSIH)
    |--------------------------------------------------------------------------
    */
    public function statusLabel()
    {
        return strtoupper($this->status);
    }

    public function statusColor()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-500',
            'paid' => 'bg-blue-600',
            'shipped' => 'bg-green-600',
            default => 'bg-gray-500',
        };
    }

    public function isShipped()
    {
        return $this->status === 'shipped';
    }
}
