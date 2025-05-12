<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get quick stats for the dashboard
        $totalCars = Car::count();
        $availableCars = Car::where('availability', true)->count();
        $totalRentals = Rental::count();
        $activeRentals = Rental::whereIn('status', ['active', 'pending'])->count();
        $totalUsers = User::where('role', 'customer')->count();
        $totalRevenue = Rental::where('status', 'completed')->sum('total_cost');

        // Get recent rentals
        $recentRentals = Rental::with(['user', 'car'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCars',
            'availableCars',
            'totalRentals',
            'activeRentals',
            'totalUsers',
            'totalRevenue',
            'recentRentals'
        ));
    }

    /**
     * Show the application settings.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update the application settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        // Validate and update settings
        $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
        ]);

        // Update settings in database or config
        // ...

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }
}
