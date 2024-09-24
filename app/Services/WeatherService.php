<?php

namespace App\Services;

use App\Repositories\WeatherRepository;
use App\Notifications\WeatherAlertNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected $weatherRepository;

    public function __construct(WeatherRepository $weatherRepository)
    {
        $this->weatherRepository = $weatherRepository;
    }

    public function checkWeatherAndNotify($city = 'London')
    {
        Log::info("Checking weather for {$city}");
        $weatherData = $this->weatherRepository->getWeatherData($city);

        if (!$weatherData) {
            Log::warning("No weather data found for {$city}");
            return;
        }

        // $precipitation = $weatherData['rain']['1h'] ?? 0; // mm in the last hour
        // $uvIndex = $this->weatherRepository->getUVIndex($weatherData['coord']['lat'], $weatherData['coord']['lon']);

        // // Default threshold values for notifications
        // $precipitationThreshold = 100; // example threshold
        // $uvIndexThreshold = 800; // example threshold

        // // If weather data exceeds thresholds, notify users
        // if ($precipitation > $precipitationThreshold || $uvIndex > $uvIndexThreshold) {
        //     $this->notifyUsers($precipitation, $uvIndex);
        // }

        $precipitation = isset($weatherData['rain']['1h']) ? $weatherData['rain']['1h'] : 0; // mm in the last hour
        Log::info("Precipitation: {$precipitation} mm");

        // Get UV index
        $uvIndex = $this->weatherRepository->getUVIndex($weatherData['coord']['lat'], $weatherData['coord']['lon']);
        Log::info("UV Index: {$uvIndex}");

        // Default threshold values for notifications
        $precipitationThreshold = 10; 
        $uvIndexThreshold = 10; 

        // If weather data exceeds thresholds, notify users
        if ($precipitation > $precipitationThreshold || $uvIndex > $uvIndexThreshold) {
            $this->notifyUsers($precipitation, $uvIndex);
        } else {
            Log::info("No notification needed: Precipitation and UV Index are within limits.");
        }
    }

    private function notifyUsers($precipitation, $uvIndex)
    {
        $users = User::all(); // Retrieve all users or filtered ones based on criteria

        foreach ($users as $user) {
            $user->notify(new WeatherAlertNotification($precipitation, $uvIndex));
        }
    }
}
