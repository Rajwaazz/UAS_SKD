<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';

    protected $fillable = [
        'films_id',
        'studio',
        'show_date',
        'total_seats',
        'start_time',
        'end_time',
        'price',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class, 'films_id');
    }

    public function Seats()
    {
        return $this->hasMany(Seats::class);
    }
}
