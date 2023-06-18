<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'admin@test.com')->first();
        if(is_null($user)){
            User::create([
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'role' => 'admin',
                'password' => bcrypt('12345678'),
            ]);
        }
    }
}
