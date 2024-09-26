<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WeatherService;
use App\Repositories\WeatherRepository;
use Mockery;

class WeatherServiceTest extends TestCase
{
    protected $weatherService;
    protected $weatherRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the WeatherRepository
        $this->weatherRepository = Mockery::mock(WeatherRepository::class);

        // Instantiate WeatherService with the mocked repository
        $this->weatherService = new WeatherService($this->weatherRepository);
    }

    public function testGetAveragedWeatherData()
    {
        // Mock data for OpenWeather, WeatherAPI, and AccuWeather
        $openWeatherData = [
            'main' => ['temp' => 15],
            'precipitation' => 0,
            'uv_index' => 5
        ];

        $weatherApiData = [
            'main' => ['temp' => 20],
            'precipitation' => 10,
            'uv_index' => 6
        ];

        $accuWeatherData = [
            'main' => ['temp' => 25],
            'precipitation' => 20,
            'uv_index' => 7
        ];

        // The expectations for each API
        $this->weatherRepository
            ->shouldReceive('getOpenWeatherData')
            ->with('London')
            ->andReturn($openWeatherData);

        $this->weatherRepository
            ->shouldReceive('getWeatherApiData')
            ->with('London')
            ->andReturn($weatherApiData);

        $this->weatherRepository
            ->shouldReceive('getAccuWeatherData')
            ->with('London')
            ->andReturn($accuWeatherData);

        // Expect the extraction of weather details for each data source
        $this->weatherRepository
            ->shouldReceive('extractWeatherDetails')
            ->with($openWeatherData, 'openweather')
            ->andReturn([
                'temp' => 15,
                'precipitation' => 0,
                'uv_index' => 5,
            ]);

        $this->weatherRepository
            ->shouldReceive('extractWeatherDetails')
            ->with($weatherApiData, 'weatherapi')
            ->andReturn([
                'temp' => 20,
                'precipitation' => 10,
                'uv_index' => 6,
            ]);

        $this->weatherRepository
            ->shouldReceive('extractWeatherDetails')
            ->with($accuWeatherData, 'accuweather')
            ->andReturn([
                'temp' => 25,
                'precipitation' => 20,
                'uv_index' => 7,
            ]);

        // Simulate averaging calculations
        $this->weatherRepository
            ->shouldReceive('average')
            ->with([15, 20, 25])
            ->andReturn(20); // Average temp

        $this->weatherRepository
            ->shouldReceive('average')
            ->with([0, 10, 20])
            ->andReturn(10); // Average precipitation

        $this->weatherRepository
            ->shouldReceive('average')
            ->with([5, 6, 7])
            ->andReturn(6); // Average UV index

        //  the service method
        $averagedWeatherData = $this->weatherService->getAveragedWeatherData('London');

        // Assert the averages returned by getAveragedWeatherData
        $this->assertEquals([
            'temp' => 20,
            'precipitation' => 10,
            'uv_index' => 6,
        ], $averagedWeatherData);
    }

    public function testGetAveragedWeatherDataWithNoData()
    {
        // Return null or empty results for all API calls
        $this->weatherRepository
            ->shouldReceive('getOpenWeatherData')
            ->with('London')
            ->andReturn(null);

        $this->weatherRepository
            ->shouldReceive('getWeatherApiData')
            ->with('London')
            ->andReturn(null);

        $this->weatherRepository
            ->shouldReceive('getAccuWeatherData')
            ->with('London')
            ->andReturn(null);

        // Expect empty weather details extraction
        $this->weatherRepository
            ->shouldReceive('extractWeatherDetails')
            ->andReturn(null);

        // Call the service method with no data
        $averagedWeatherData = $this->weatherService->getAveragedWeatherData('London');

        // Assert that no data is returned
        $this->assertNull($averagedWeatherData);
    }
}
