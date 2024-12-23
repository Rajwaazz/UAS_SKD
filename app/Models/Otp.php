<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otp';

    protected $fillable = [
        'email',
        'otp',
        'expired_at',
        'verified',
    ];

    public function scopeValidOTP($query, $email, $otp)
    {
        return $query->where('email', $email)
            ->where('otp', $otp)
            ->where('expired_at', '>', now())
            ->where('verified', false);
    }

    public function isExpired() : bool
    {
        return $this->expired_at->isPast();
    }
}
