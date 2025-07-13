<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptchaLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'phone', 'status', 'ip_address', 'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

