<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class FetchWeatherData extends Command
{
    protected $signature = 'weather:fetch';
    protected $description = 'Fetch weather data and send notifications for anomalies';
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Check if the notifications are paused for the user
            if ($user->notification_paused_until && now()->isBefore($user->notification_paused_until)) {
                Log::info("Notifications are paused for {$user->name}. No notifications will be sent.");
                continue; // Skip this user and move on to the next
            }

            // Process each city for the user if notifications are not paused
            $cities = $user->userCities; // Get cities for each user

            foreach ($cities as $city) {
                $this->notificationService->checkWeatherAndNotify($user, $city->city_name);
            }
        }
    }
}
