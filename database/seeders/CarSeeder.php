<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            [
                'name' => 'Toyota Camry',
                'brand' => 'Toyota',
                'model' => 'Camry',
                'year' => 2022,
                'car_type' => 'Sedan',
                'daily_rent_price' => 45.00,
                'availability' => true,
                'image' => 'cars/toyota-camry.jpg',
            ],
            [
                'name' => 'Honda Accord',
                'brand' => 'Honda',
                'model' => 'Accord',
                'year' => 2021,
                'car_type' => 'Sedan',
                'daily_rent_price' => 42.00,
                'availability' => true,
                'image' => 'cars/honda-accord.jpg',
            ],
            [
                'name' => 'Ford Explorer',
                'brand' => 'Ford',
                'model' => 'Explorer',
                'year' => 2022,
                'car_type' => 'SUV',
                'daily_rent_price' => 65.00,
                'availability' => true,
                'image' => 'cars/ford-explorer.jpg',
            ],
            [
                'name' => 'Chevrolet Tahoe',
                'brand' => 'Chevrolet',
                'model' => 'Tahoe',
                'year' => 2021,
                'car_type' => 'SUV',
                'daily_rent_price' => 75.00,
                'availability' => true,
                'image' => 'cars/chevrolet-tahoe.jpg',
            ],
            [
                'name' => 'BMW 3 Series',
                'brand' => 'BMW',
                'model' => '3 Series',
                'year' => 2022,
                'car_type' => 'Sedan',
                'daily_rent_price' => 85.00,
                'availability' => true,
                'image' => 'cars/bmw-3-series.jpg',
            ],
            [
                'name' => 'Mercedes-Benz C-Class',
                'brand' => 'Mercedes-Benz',
                'model' => 'C-Class',
                'year' => 2021,
                'car_type' => 'Sedan',
                'daily_rent_price' => 90.00,
                'availability' => true,
                'image' => 'cars/mercedes-c-class.jpg',
            ],
            [
                'name' => 'Audi A4',
                'brand' => 'Audi',
                'model' => 'A4',
                'year' => 2022,
                'car_type' => 'Sedan',
                'daily_rent_price' => 88.00,
                'availability' => true,
                'image' => 'cars/audi-a4.jpg',
            ],
            [
                'name' => 'Volkswagen Golf',
                'brand' => 'Volkswagen',
                'model' => 'Golf',
                'year' => 2021,
                'car_type' => 'Hatchback',
                'daily_rent_price' => 40.00,
                'availability' => true,
                'image' => 'cars/volkswagen-golf.jpg',
            ],
            [
                'name' => 'Mazda CX-5',
                'brand' => 'Mazda',
                'model' => 'CX-5',
                'year' => 2022,
                'car_type' => 'SUV',
                'daily_rent_price' => 55.00,
                'availability' => true,
                'image' => 'cars/mazda-cx5.jpg',
            ],
            [
                'name' => 'Hyundai Tucson',
                'brand' => 'Hyundai',
                'model' => 'Tucson',
                'year' => 2021,
                'car_type' => 'SUV',
                'daily_rent_price' => 50.00,
                'availability' => true,
                'image' => 'cars/hyundai-tucson.jpg',
            ],
            [
                'name' => 'Kia Sportage',
                'brand' => 'Kia',
                'model' => 'Sportage',
                'year' => 2022,
                'car_type' => 'SUV',
                'daily_rent_price' => 52.00,
                'availability' => true,
                'image' => 'cars/kia-sportage.jpg',
            ],
            [
                'name' => 'Nissan Altima',
                'brand' => 'Nissan',
                'model' => 'Altima',
                'year' => 2021,
                'car_type' => 'Sedan',
                'daily_rent_price' => 43.00,
                'availability' => true,
                'image' => 'cars/nissan-altima.jpg',
            ],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
