<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = 'categories'; // Specify the table name explicitly

    public function foods()
    {
        return $this->hasMany(Food::class, 'category_id');
    }
}

