<?php

namespace Database\Seeders;
use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        // Get customer users
        $customers = User::where('role', 'customer')->get();

        // Get all cars
        $cars = Car::all();

        // Create some sample rentals
        $rentals = [
            [
                'user_id' => $customers[0]->id,
                'car_id' => $cars[0]->id,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->subDays(5),
                'total_cost' => $cars[0]->daily_rent_price * 6, // 6 days
                'status' => 'completed',
            ],
            [
                'user_id' => $customers[1]->id,
                'car_id' => $cars[1]->id,
                'start_date' => Carbon::now()->subDays(8),
                'end_date' => Carbon::now()->subDays(3),
                'total_cost' => $cars[1]->daily_rent_price * 6, // 6 days
                'status' => 'completed',
            ],
            [
                'user_id' => $customers[2]->id,
                'car_id' => $cars[2]->id,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(2),
                'total_cost' => $cars[2]->daily_rent_price * 8, // 8 days
                'status' => 'ongoing',
            ],
            [
                'user_id' => $customers[0]->id,
                'car_id' => $cars[3]->id,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(10),
                'total_cost' => $cars[3]->daily_rent_price * 6, // 6 days
                'status' => 'ongoing',
            ],
            [
                'user_id' => $customers[1]->id,
                'car_id' => $cars[4]->id,
                'start_date' => Carbon::now()->addDays(3),
                'end_date' => Carbon::now()->addDays(8),
                'total_cost' => $cars[4]->daily_rent_price * 6, // 6 days
                'status' => 'ongoing',
            ],
            [
                'user_id' => $customers[2]->id,
                'car_id' => $cars[5]->id,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->subDays(10),
                'total_cost' => $cars[5]->daily_rent_price * 6, // 6 days
                'status' => 'canceled',
            ],
        ];

        foreach ($rentals as $rental) {
            Rental::create($rental);
        }
    }
}
