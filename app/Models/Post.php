<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'post_type',
        'meta_data',
        'category_id',
        'author_id',
    ];
}
