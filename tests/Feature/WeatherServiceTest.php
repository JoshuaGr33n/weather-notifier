<?php 

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WeatherService;
use App\Repositories\WeatherRepository;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WeatherAlertNotification;
use App\Models\User; 
use Mockery;

class WeatherServiceTest extends TestCase
{
    protected $weatherService;
    protected $weatherRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user for testing
        $this->user = User::factory()->create(); // Ensure you have users to test against

        // Mock the WeatherRepository
        $this->weatherRepository = Mockery::mock(WeatherRepository::class);
        $this->weatherService = new WeatherService($this->weatherRepository);
    }

    public function testCheckWeatherAndNotify()
    {
        Notification::fake();

        // Simulate a case where the precipitation exceeds the threshold
        $this->weatherRepository
            ->shouldReceive('getWeatherData')
            ->with('London')
            ->andReturn([
                'rain' => ['1h' => 101], // Exceeds threshold
                'coord' => ['lat' => 51.5085, 'lon' => -0.1257],
            ]);

        $this->weatherRepository
            ->shouldReceive('getUVIndex')
            ->andReturn(0); // Return a UV index value (if necessary)

        // Call the method
        $this->weatherService->checkWeatherAndNotify($this->user, 'London', 10, 10); // Make sure to pass user thresholds

        // Assert that the notification was sent
        Notification::assertSentTo(
            [$this->user], // Assert that notification is sent to the created user
            WeatherAlertNotification::class
        );
    }

    public function testNoNotificationWhenUnderThreshold()
    {
        Notification::fake();

        // Simulate a case where the precipitation does not exceed the threshold
        $this->weatherRepository
            ->shouldReceive('getWeatherData')
            ->with('Paris')
            ->andReturn([
                'rain' => ['1h' => 0], // Under threshold
                'coord' => ['lat' => 48.8566, 'lon' => 2.3522],
            ]);

        $this->weatherRepository
            ->shouldReceive('getUVIndex')
            ->andReturn(0); // Return a UV index value (if necessary)

        // Call the method
        $this->weatherService->checkWeatherAndNotify($this->user, 'Paris', 10, 10); // Use thresholds

        // Assert that no notification was sent
        Notification::assertNotSentTo([$this->user], WeatherAlertNotification::class);
    }
}
