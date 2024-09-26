<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Inertia\Inertia;



class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function showDashboard()
    {
        $city = 'London';
        $averagedWeather = $this->weatherService->getAveragedWeatherData($city) ?? [];

        return Inertia::render('Dashboard', [
            'cityName' => $city,
            'averagedWeather' => [
                'temp' => $averagedWeather['temp'] ?? null,
                'precipitation' => $averagedWeather['precipitation'] ?? null,
                'uv_index' => $averagedWeather['uv_index'] ?? null,
            ],
        ]);
    }
}
