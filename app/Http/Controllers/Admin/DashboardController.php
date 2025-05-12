<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total number of cars
        $totalCars = Car::count();

        // Number of available cars
        $availableCars = Car::where('availability', true)->count();

        // Total number of rentals
        $totalRentals = Rental::count();

        // Total earnings from rentals
        $totalEarnings = Rental::where('status', '!=', 'canceled')->sum('total_cost');

        // Recent rentals
        $recentRentals = Rental::with(['car', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly earnings chart data
        $monthlyEarnings = Rental::where('status', '!=', 'canceled')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_cost) as earnings'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('earnings', 'month')
            ->toArray();

        // Fill in missing months with zero
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlyEarnings[$i])) {
                $monthlyEarnings[$i] = 0;
            }
        }
        ksort($monthlyEarnings);

        // Car type distribution
        $carTypeDistribution = Car::select('car_type', DB::raw('count(*) as count'))
            ->groupBy('car_type')
            ->get()
            ->pluck('count', 'car_type')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalCars',
            'availableCars',
            'totalRentals',
            'totalEarnings',
            'recentRentals',
            'monthlyEarnings',
            'carTypeDistribution'
        ));
    }
}
