<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddCashier extends Model
{
    use HasFactory;

    protected $table = 'addcashier';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'email',
        'password',
        'google_id',
    ];

    protected $attributes = [
        'roles' => 'cashier', // Set the default value for 'roles' attribute to 'cashier'
    ];

    // Define the relationship with User model
    protected static function boot()
{
    parent::boot();

    // Deleting event
    static::deleting(function ($addcashier) {
        \Log::info("Deleting event triggered for AddCashier with email: {$addcashier->email}");
        $deleted = DB::table('users')->where('email', $addcashier->email)->delete();
        \Log::info("User record deleted: {$deleted}");
    });

    // Updating event
    static::updating(function ($addcashier) {
        \Log::info("Updating event triggered for AddCashier with email: {$addcashier->email}");
        $updated = DB::table('users')
            ->where('email', $addcashier->email)
            ->update([
                'name' => $addcashier->name,
                'email' => $addcashier->email,
                'password' => Hash::make($addcashier->password),
            ]);
        \Log::info("User record updated: {$updated}");
    });
    
}
public function update(array $attributes = [], array $options = [])
    {
        // If any of the first name, middle name, or last name is being updated, update the 'name' field
        if (isset($attributes['first_name']) || isset($attributes['middle_name']) || isset($attributes['last_name'])) {
            $attributes['name'] = trim("{$attributes['first_name']} {$attributes['middle_name']} {$attributes['last_name']}");
        }

        return parent::update($attributes, $options);
    }
}
