<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nisn_nip' => '1234567890',
            'name' => 'Kayla Putrie',
            'username' => 'kayla',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'class_id' => null,
            'is_active' => true,
        ]);
    }
}