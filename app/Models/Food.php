<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = ['name', 'price', 'category_id', 'photo','number_of_items'];
    protected $table = 'foods';
    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'category_id');
    }
}
