<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\NotificationService;
use App\Repositories\WeatherRepository;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WeatherAlertNotification;
use App\Models\User; 
use Mockery;

class NotificationServiceTest extends TestCase
{
    protected $notificationService; 
    protected $weatherRepository;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user for testing
        $this->user = User::factory()->create();

        // Mock the WeatherRepository
        $this->weatherRepository = Mockery::mock(WeatherRepository::class);
        $this->notificationService = new NotificationService($this->weatherRepository); 
    }

    public function testCheckWeatherAndNotify()
    {
        Notification::fake();

        // Exceeds the threshold simulation
        $this->weatherRepository
            ->shouldReceive('getOpenWeatherData')
            ->with('London')
            ->andReturn([
                'rain' => ['1h' => 101], // Exceeds threshold
                'coord' => ['lat' => 51.5085, 'lon' => -0.1257],
            ]);

        $this->weatherRepository
            ->shouldReceive('getUVIndex')
            ->andReturn(0); // UV index value

        // Call the method on NotificationService
        $this->notificationService->checkWeatherAndNotify($this->user, 'London'); // Use correct method call

        // Assert that the notification was sent
        Notification::assertSentTo(
            [$this->user], // Assert that notification is sent to the created user
            WeatherAlertNotification::class
        );
    }

    public function testNoNotificationWhenUnderThreshold()
    {
        Notification::fake();

        // Under the threshold
        $this->weatherRepository
            ->shouldReceive('getOpenWeatherData')
            ->with('Paris')
            ->andReturn([
                'rain' => ['1h' => 0], // Under threshold
                'coord' => ['lat' => 48.8566, 'lon' => 2.3522],
            ]);

        $this->weatherRepository
            ->shouldReceive('getUVIndex')
            ->andReturn(0); // UV index value

        // Call the method on NotificationService
        $this->notificationService->checkWeatherAndNotify($this->user, 'Paris'); 

        // Assert that no notification was sent
        Notification::assertNotSentTo([$this->user], WeatherAlertNotification::class);
    }
}
