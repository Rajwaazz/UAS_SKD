<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'nama',
        'price',
    ];

    public function booking()
    {
        return $this->belongsToMany(Booking::class, 'booking-service', 'service_id', 'bookings_id')->withPivot('jumlah')->withTimestamps();
    }
}
