<?php

// app/Models/VerificationCode.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $fillable = [
        'phone', 'code', 'expires_at', 'is_expired',
    ];

    public $timestamps = true;

    public function isValid()
    {
        return !$this->is_expired && $this->expires_at > now();
    }
}
