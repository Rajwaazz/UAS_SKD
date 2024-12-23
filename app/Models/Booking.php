<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\service;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'seat_id',
        'total_price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function bookingseat()
    {
        return $this->belongsToMany(Seats::class, 'booking-seat', 'booking_id', 'seat_id')->withTimestamps();
    }

    public function bookingservice()
    {
        return $this->belongsToMany(service::class, 'booking-service', 'bookings_id', 'service_id')->withPivot('jumlah')->withTimestamps();
    }
}
