<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'condition_id',
        'name',
        'description',
        'price',
        'status',
        'image_path',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
