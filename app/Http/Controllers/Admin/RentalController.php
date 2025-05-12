<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Car;
use App\Models\User;
use App\Models\Rental;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['car', 'user'])->latest()->paginate(10);
        return view('admin.rentals.index', compact('rentals'));
    }

    public function create()
    {
        $cars = Car::where('availability', true)->get();
        $customers = User::where('role', 'customer')->get();
        return view('admin.rentals.create', compact('cars', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:ongoing,completed,canceled',
        ]);

        $car = Car::findOrFail($request->car_id);

        if (!$car->isAvailableForDates($request->start_date, $request->end_date)) {
            return back()->withErrors(['car_id' => 'The selected car is not available for the chosen dates.'])->withInput();
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1; // Include both start and end days

        $validated['total_cost'] = $days * $car->daily_rent_price;

        Rental::create($validated);

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Rental created successfully.');
    }

    public function show(Rental $rental)
    {
        return view('admin.rentals.show', compact('rental'));
    }

    public function edit(Rental $rental)
    {
        $cars = Car::all();
        $customers = User::where('role', 'customer')->get();
        return view('admin.rentals.edit', compact('rental', 'cars', 'customers'));
    }

    public function update(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:ongoing,completed,canceled',
        ]);

        $car = Car::findOrFail($request->car_id);

        // Only check availability if the car or dates have changed
        if (($rental->car_id != $request->car_id ||
             $rental->start_date->format('Y-m-d') != $request->start_date ||
             $rental->end_date->format('Y-m-d') != $request->end_date) &&
            !$car->isAvailableForDates($request->start_date, $request->end_date, $rental->id)) {
            return back()->withErrors(['car_id' => 'The selected car is not available for the chosen dates.'])->withInput();
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1; // Include both start and end days

        $validated['total_cost'] = $days * $car->daily_rent_price;

        $rental->update($validated);

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Rental updated successfully.');
    }

    public function destroy(Rental $rental)
    {
        $rental->delete();

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Rental deleted successfully.');
    }
}
