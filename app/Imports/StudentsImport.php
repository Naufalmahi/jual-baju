<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'nisn_nip'  => $row['nisn'],
            'name'      => $row['nama'],
            'username'  => $row['username'],
            'password'  => Hash::make($row['nisn']), // Password default menggunakan NISN
            'role'      => 'siswa',
            'class_id'  => $row['id_kelas'],
            'is_active' => true,
        ]);
    }
}