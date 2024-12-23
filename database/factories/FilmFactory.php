<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

class FilmFactory extends Factory
{
    protected $model = Film::class;

    public function definition()
    {
        return [
            'poster' => 'default.jpg', // Ganti dengan pengaturan sesuai
            'judul' => $this->faker->sentence,
            'deskripsi' => $this->faker->paragraph,
            'genre' => $this->faker->word,
            'tanggalRilis' => $this->faker->date,
            'duration' => $this->faker->numberBetween(90, 180),
        ];
    }
}
