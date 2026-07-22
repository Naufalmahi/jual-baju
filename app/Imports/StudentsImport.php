<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari ID Kelas berdasarkan kombinasi Nama Kelas/Jurusan dari Excel
        $class = ClassModel::where('name', $row['kelas'] ?? '')->first();

        return new User([
            'nisn_nip'  => $row['nisn'] ?? $row['nis'],
            'name'      => $row['nama'],
            'username'  => $row['nisn'] ?? $row['nis'], // Username default disamakan dengan NISN
            'password'  => Hash::make($row['password'] ?? '12345678'), // Default password jika kosong
            'role'      => 'siswa',
            'class_id'  => $class ? $class->id : null,
            'is_active' => true,
        ]);
    }
}