<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_type',
        'delivery_address',
        'delivery_date',
        'delivery_time',
        'pickup_date',
        'pickup_time',
        'preparation_note',
        'delivery_note',
        'total_amount',
        'tax_amount',
        'delivery_fee',
        'status',
        'payment_method',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
