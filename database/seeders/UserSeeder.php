<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nama_role', 'admin')->first();
        $ownerRole = Role::where('nama_role', 'owner')->first();

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'status' => 'aktif',
            'no_hp' => '081234567890',
        ]);

        User::create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $ownerRole->id,
            'status' => 'aktif',
            'no_hp' => '081234567891',
        ]);
    }
}
