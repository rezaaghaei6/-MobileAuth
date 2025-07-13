<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // فیلدهای قابل مقداردهی جمعی
    protected $fillable = [
        'name',
        'phone',
        'role',
    ];

    // Helper method برای بررسی admin بودن
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Accessor برای سازگاری با کدهای قدیمی
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }
}