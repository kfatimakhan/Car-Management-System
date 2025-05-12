<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        // Apply filters
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('car_type')) {
            $query->where('car_type', $request->car_type);
        }

        if ($request->filled('min_price')) {
            $query->where('daily_rent_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('daily_rent_price', '<=', $request->max_price);
        }

        // Only show available cars by default, unless specifically requested to show all
        if (!$request->has('show_all') || !$request->show_all) {
            $query->where('availability', true);
        }

        // Get unique brands and car types for filter dropdowns
        $brands = Car::distinct()->pluck('brand');
        $carTypes = Car::distinct()->pluck('car_type');
        $minPrice = Car::min('daily_rent_price');
        $maxPrice = Car::max('daily_rent_price');

        $cars = $query->latest()->paginate(9);

        return view('frontend.cars.index', compact('cars', 'brands', 'carTypes', 'minPrice', 'maxPrice'));
    }

    public function show(Car $car)
    {
        return view('frontend.cars.show', compact('car'));
    }

    public function checkAvailability(Request $request, Car $car)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $isAvailable = $car->isAvailableForDates($startDate, $endDate);

        return response()->json([
            'available' => $isAvailable,
        ]);
    }
}
