<?php

namespace App\Services;

use App\Repositories\WeatherRepository;
use App\Notifications\WeatherAlertNotification;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class WeatherService
{
    protected $weatherRepository;

    public function __construct(WeatherRepository $weatherRepository)
    {
        $this->weatherRepository = $weatherRepository;
    }

    // Function to calculate average temperature, precipitation, etc.
    public function getAveragedWeatherData($city)
    {
        $openWeatherData = $this->weatherRepository->getOpenWeatherData($city);
        $weatherApiData = $this->weatherRepository->getWeatherApiData($city);
        $accuWeatherData = $this->weatherRepository->getAccuWeatherData($city);

        $openWeather = $this->weatherRepository->extractWeatherDetails($openWeatherData, 'openweather');
        $weatherApi = $this->weatherRepository->extractWeatherDetails($weatherApiData, 'weatherapi');
        $accuWeather = $this->weatherRepository->extractWeatherDetails($accuWeatherData, 'accuweather');

        $weatherData = [$openWeather, $weatherApi, $accuWeather];
        $filteredData = array_filter($weatherData); // Remove null responses

        if (empty($filteredData)) {
            return null; // If no valid data from any service
        }

        $averageTemp = $this->weatherRepository->average(array_column($filteredData, 'temp'));
        $averagePrecipitation = $this->weatherRepository->average(array_column($filteredData, 'precipitation'));
        $averageUV = $this->weatherRepository->average(array_column($filteredData, 'uv_index'));

        return [
            'temp' => $averageTemp,
            'precipitation' => $averagePrecipitation,
            'uv_index' => $averageUV,
        ];
    }
}
