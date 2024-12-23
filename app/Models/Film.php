<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $table = 'films';

    protected $fillable = [
        'poster',
        'judul',
        'deskripsi',
        'genre',
        'tanggalRilis',
        'duration',
        'status',
    ];

    public function getPosterUrlAttribute()
    {
        if ($this->poster) {
            return asset('storage/' . $this->poster);
        }
        return asset('images/default-poster.png'); // Gambar default jika tidak ada poster
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    
}
