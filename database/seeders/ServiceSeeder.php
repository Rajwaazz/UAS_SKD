<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            ['nama' => 'Popcorn Kecil', 'price' => 20000],
            ['nama' => 'Popcorn Besar', 'price' => 35000],
            ['nama' => 'Minuman Soda', 'price' => 15000],
            ['nama' => 'Kursi Premium', 'price' => 50000],
            ['nama' => 'Sewa Bantal', 'price' => 10000],
            ['nama' => 'Sewa Selimut', 'price' => 15000],
            ['nama' => 'Sewa Kacamata 3D', 'price' => 25000],
            ['nama' => 'Parkir Prioritas', 'price' => 30000],
            ['nama' => 'Souvenir Poster', 'price' => 20000],
            ['nama' => 'Voucher Diskon Film Berikutnya', 'price' => 10000],
        ];

        foreach ($services as $service) {
            service::create($service);
        }
    }
}
