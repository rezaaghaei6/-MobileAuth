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

    // اگر بخواهی نقش‌های پیش‌فرض یا helper method اضافه کنی می‌توانی اینجا اضافه کنی
}
