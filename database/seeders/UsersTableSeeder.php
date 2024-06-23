<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name'  => 'Jhanelle',
            'middle_name'  => '',
            'last_name'  => 'Rafer',
            'name' => 'Jhanelle Rafer',
            'email' => 'jhnlrafer@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Admin12345!'),
            'roles' => 'manager',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
