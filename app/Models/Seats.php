<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seats extends Model
{
    use HasFactory;

    protected $table = 'seats';

    protected $fillable = [
        'schedule_id',
        'seat_number',
        'status',
    ];

    public function Schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function seatbooking()
    {
        return $this->belongsToMany(Booking::class, 'booking-seat');
    }
}
