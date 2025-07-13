<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaptchaLog extends Model
{
    protected $fillable = ['user_id', 'success', 'ip', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
