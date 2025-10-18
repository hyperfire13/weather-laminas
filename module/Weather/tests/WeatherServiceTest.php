<?php
declare(strict_types=1);

namespace WeatherTest\Service;

use PHPUnit\Framework\TestCase;
use Weather\Service\WeatherService;
use Weather\Exception\WeatherException;

final class WeatherServiceTest extends TestCase
{
    private WeatherService $weatherService;

    protected function setUp(): void
    {
        $this->weatherService = new WeatherService('invalid_api_key');
    }

    public function testWeatherServiceThrowsExceptionForInvalidCity(): void
    {
        $this->expectException(WeatherException::class);
        $this->weatherService->getWeather('FakeCity123');
    }

    public function testWeatherServiceHasGetWeatherMethod(): void
    {
        $this->assertTrue(method_exists($this->weatherService, 'getWeather'));
    }
}
