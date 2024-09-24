<?php 

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class WeatherRepository
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHER_API_KEY');
    }

    public function getWeatherData($city)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}");
        
        if ($response->ok()) {
            return $response->json();
        }

        return null;
    }

    public function getUVIndex($lat, $lon)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/uvi?lat={$lat}&lon={$lon}&appid={$this->apiKey}");

        if ($response->ok()) {
            return $response->json()['value'];
        }

        return null;
    }
}
