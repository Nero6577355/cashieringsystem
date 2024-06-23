<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = ['cashier_id', 'total_quantity', 'total_price', 'transaction_id','status'];
    public static $statusRules = [
        'status' => 'required|in:Unpaid,Paid,Cancelled', // Add other possible statuses if needed
    ];

    public function getStatusAttribute($value)
    {
        return ucfirst($value); 
    }

    protected $dates = ['created_at']; 
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
    public function orderItems()
    {
        return $this->hasMany(Order::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'cashier_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
