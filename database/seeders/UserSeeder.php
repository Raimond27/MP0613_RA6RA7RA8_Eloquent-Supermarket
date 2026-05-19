<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice.johnson@example.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'customer')->first()->id
        ]);

        User::create([
            'name' => 'Bob Martinez',
            'email' => 'bob.martinez@example.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'employee')->first()->id
        ]);

        User::create([
            'name' => 'Carol Williams',
            'email' => 'carol.williams@example.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);
    }
}
