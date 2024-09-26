<?php

namespace App\Services;

use App\Repositories\WeatherRepository;
use App\Notifications\WeatherAlertNotification;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class NotificationService
{
    protected $weatherRepository;

    public function __construct(WeatherRepository $weatherRepository)
    {
        $this->weatherRepository = $weatherRepository;
    }

    public function checkWeatherAndNotify($user, $city)
    {
        Log::info("Checking weather for {$city}");
        $weatherData = $this->weatherRepository->getOpenWeatherData($city);

        if (!$weatherData) {
            Log::warning("No weather data found for {$city}");
            return;
        }

        $precipitationThreshold = $user->precipitation_threshold;
        $uvIndexThreshold = $user->uv_index_threshold;

        $precipitation = isset($weatherData['rain']['1h']) ? $weatherData['rain']['1h'] : 0; // mm in the last hour
        Log::info("Precipitation: {$precipitation} mm");

        // Get UV index
        $uvIndex = $this->weatherRepository->getUVIndex($weatherData['coord']['lat'], $weatherData['coord']['lon']);
        Log::info("UV Index: {$uvIndex}");

        // If weather data exceeds thresholds, notify users
        if ($precipitation > $precipitationThreshold || $uvIndex > $uvIndexThreshold) {
            $this->notifyUsers($precipitation, $uvIndex, $city, $user);
        } else {
            Log::info("No notification needed for {$city}: Precipitation and UV Index are within limits.");
        }
    }

    private function notifyUsers($precipitation, $uvIndex, $city, $user)
    {
        $user->notify(new WeatherAlertNotification($city, $user->name, $precipitation, $uvIndex));
    }
}
