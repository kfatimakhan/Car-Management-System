<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use App\Notifications\RentalConfirmation;
use App\Notifications\AdminRentalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = auth()->user()->rentals()->with('car')->latest()->get();
        return view('frontend.rentals.index', compact('rentals'));
    }

    public function create(Request $request, Car $car)
    {
        return view('frontend.rentals.create', compact('car'));
    }

    public function store(Request $request, Car $car)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if (!$car->isAvailableForDates($request->start_date, $request->end_date)) {
            return back()->withErrors(['unavailable' => 'The car is not available for the selected dates.'])->withInput();
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1; // Include both start and end days

        $totalCost = $days * $car->daily_rent_price;

        $rental = Rental::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_cost' => $totalCost,
            'status' => 'ongoing',
        ]);

        // Send confirmation email to customer
        auth()->user()->notify(new RentalConfirmation($rental));

        // Send notification to admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new AdminRentalNotification($rental));

        return redirect()->route('rentals.index')
            ->with('success', 'Car rental booked successfully!');
    }

    public function show(Rental $rental)
    {
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        return view('frontend.rentals.show', compact('rental'));
    }

    public function cancel(Rental $rental)
    {
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$rental->canBeCanceled()) {
            return back()->withErrors(['cannot_cancel' => 'This rental cannot be canceled.']);
        }

        $rental->update(['status' => 'canceled']);

        return redirect()->route('rentals.index')
            ->with('success', 'Rental canceled successfully.');
    }
}
