<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService;
use App\Models\User;


class FetchWeatherData extends Command
{
    protected $signature = 'weather:fetch';
    protected $description = 'Fetch weather data and send notifications for anomalies';
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    public function handle()
    {
        $users = User::all(); 

        foreach ($users as $user) {
            $cities = $user->userCities; // Get cities for each user

            foreach ($cities as $city) {
                $this->weatherService->checkWeatherAndNotify($user,
                    $city->city_name
                );
            }
        }
    }
}
