<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCityController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index()
    {
        // Get cities for the authenticated user
        $userCities = $this->user->userCities;

        return inertia('Profile/Partials/CityList', [
            'userCities' => $userCities,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the city name
        $this->validateCityName($request);

        // Add the city for the authenticated user
        $this->user->userCities()->create([
            'city_name' => $request->city_name,
        ]);

        return redirect()->route('user-cities.index');
    }

    // Helper method to validate the city name
    private function validateCityName(Request $request)
    {
        $request->validate([
            'city_name' => 'required|string|max:255',
        ]);
    }
}
