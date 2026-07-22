<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Tambahkan 'class_id' ke dalam $fillable jika belum ada
    protected $fillable = [
        'nisn_nip',
        'name',
        'username',
        'email',
        'password',
        'role',
        'class_id',
        'is_active',
    ];

    /**
     * Relasi ke ClassModel (User/Siswa terhubung ke 1 Kelas)
     */
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}