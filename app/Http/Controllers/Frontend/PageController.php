<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $featuredCars = Car::where('availability', true)
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.home', compact('featuredCars'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you would typically send an email or store the contact form submission
        // For now, we'll just redirect with a success message

        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
