<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class WeatherRepository
{
    protected $apiKeys;

    public function __construct()
    {
        // $this->apiKey = env('OPENWEATHER_API_KEY');
        $this->apiKeys = [
            'openweather' => env('OPENWEATHER_API_KEY'),
            'weatherapi' => env('WEATHERAPI_KEY'),
            'accuweather' => env('ACCUWEATHER_KEY'),
        ];
    }

    // public function getWeatherData($city)
    // {
    //     $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}");

    //     if ($response->ok()) {
    //         return $response->json();
    //     }

    //     return null;
    // }

    // Fetch data from OpenWeatherMap
    public function getOpenWeatherData($city)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKeys['openweather']}&units=metric");
        if ($response->ok()) {
            return $response->json();
        }
        return null;
    }

    // Fetch data from WeatherAPI
    public function getWeatherApiData($city)
    {
        $response = Http::get("http://api.weatherapi.com/v1/current.json?key={$this->apiKeys['weatherapi']}&q={$city}");
        if ($response->ok()) {
            return $response->json();
        }
        return null;
    }

    // Fetch data from AccuWeather
    public function getAccuWeatherData($city)
    {
        $locationKey = $this->getAccuWeatherLocationKey($city);

        if (!$locationKey) {
            return null; // If invalid location key, return null
        }

        $response = Http::get("http://dataservice.accuweather.com/currentconditions/v1/{$locationKey}?apikey={$this->apiKeys['accuweather']}");

        if ($response->ok()) {
            return $response->json();
        }

        return null; // Return null if the response is not ok
    }

    // Fetch uv Index
    public function getUVIndex($lat, $lon)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/uvi?lat={$lat}&lon={$lon}&appid={$this->apiKeys['openweather']}");

        if ($response->ok()) {
            return $response->json()['value'];
        }

        return null;
    }

    // Helper function to calculate averages
    public function average($values)
    {
        return array_sum($values) / count($values);
    }

    private function getAccuWeatherLocationKey($city)
    {
        $response = Http::get("http://dataservice.accuweather.com/locations/v1/cities/search?apikey={$this->apiKeys['accuweather']}&q={$city}");

        if ($response->ok() && isset($response->json()[0]['Key'])) {
            return $response->json()[0]['Key']; // Return the location key
        }

        return null; // Return null if no valid key is found
    }

    public function extractWeatherDetails($data, $service)
    {
        if (!$data) {
            return null;
        }

        switch ($service) {
            case 'openweather':
                return [
                    'temp' => $data['main']['temp'],
                    'precipitation' => $data['rain']['1h'] ?? 0,
                    'uv_index' => $this->getUVIndex($data['coord']['lat'], $data['coord']['lon']),
                ];

            case 'weatherapi':
                return [
                    'temp' => $data['current']['temp_c'],
                    'precipitation' => $data['current']['precip_mm'] ?? 0,
                    'uv_index' => $data['current']['uv'] ?? 0,
                ];

            case 'accuweather':
                if (!isset($data[0])) {
                    return null;
                }
                $weatherData = $data[0];
                return [
                    'temp' => $weatherData['Temperature']['Metric']['Value'],
                    'precipitation' => $weatherData['HasPrecipitation'] ? ($weatherData['PrecipitationType'] ?? 0) : 0,
                    'uv_index' => 0, // AccuWeather does not provide UV index so its defalut to 0
                ];

            default:
                return null; // Unsupported service
        }
    }
}
