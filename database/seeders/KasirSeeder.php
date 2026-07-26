<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KasirSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nisn_nip' => '1987654322',
            'name' => 'Kasir',
            'username' => 'kasir',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
            'is_active' => true,
        ]);
    }
}