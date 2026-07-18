<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User; 
use App\Models\Product; 

class Review extends Model
{
    use HasFactory;

    // Add these constants at the top of the class
    const MIN_RATING = 1;
    const MAX_RATING = 5;

    protected $fillable = [
        'user_id', 'product_id', 'order_id', 'rating', 'comment', 'rider_rating', 'rider_comment', 'media'
    ];

    protected $casts = [
        'media' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}