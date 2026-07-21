<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nisn_nip',
        'name',
        'username',
        'password',
        'role',
        'class_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Kelas
    public function schoolClass()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}