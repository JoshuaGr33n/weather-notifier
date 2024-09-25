<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCity;
use Illuminate\Support\Facades\Auth;

class UserCityController extends Controller
{
    public function index()
    {
        // Get cities for the authenticated user
        $userCities = Auth::user()->userCities;

        return inertia('Profile/Partials/CityList', [
            'userCities' => $userCities,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the city name
        $request->validate([
            'city_name' => 'required|string|max:255',
        ]);
        // Add the city for the authenticated user
        Auth::user()->userCities()->create([
            'city_name' => $request->city_name,
        ]);

        return redirect()->route('user-cities.index');
    }
}
