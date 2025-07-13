<?php

// app/Models/VerificationCode.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $fillable = [
        'phone',
        'code',
        'expires_at',
        'is_expired',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_expired' => 'boolean',
    ];

    public $timestamps = true;
}
