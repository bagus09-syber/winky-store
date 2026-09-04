<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'description' => 'Produsen iPhone, MacBook, dan perangkat Apple lainnya'],
            ['name' => 'Samsung', 'description' => 'Brand elektronik terbesar dari Korea Selatan'],
            ['name' => 'Xiaomi', 'description' => 'Brand teknologi asal China dengan harga terjangkau'],
            ['name' => 'OPPO', 'description' => 'Brand smartphone dengan kamera terbaik'],
            ['name' => 'Vivo', 'description' => 'Brand smartphone inovatif dari China'],
            ['name' => 'ASUS', 'description' => 'Brand laptop dan smartphone gaming'],
            ['name' => 'Lenovo', 'description' => 'Produsen laptop dan perangkat bisnis'],
            ['name' => 'Anker', 'description' => 'Brand aksesoris dan power bank premium'],
            ['name' => 'Spigen', 'description' => 'Brand case dan pelindung smartphone'],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'description' => $brand['description'],
                'is_active' => true,
            ]);
        }
    }
}
