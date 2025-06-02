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

    protected $table = 'review';
    protected $fillable = [
        'user_id', 'product_id', 'rating', 'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}