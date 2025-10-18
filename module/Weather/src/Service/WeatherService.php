<?php
namespace Weather\Service;

use Laminas\Http\Client;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;
use Weather\Exception\WeatherException;

class WeatherService
{
    private string $apiKey;
    private Logger $logger;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        $logDir = __DIR__ . '/../../../data/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $this->logger = new Logger();
        $this->logger->addWriter(new Stream($logDir . '/weather.log'));
    }

    public function getWeather(string $city): array
    {
        try {
            $client = new Client("https://api.openweathermap.org/data/2.5/weather");
            $client->setMethod('GET');
            $client->setParameterGet([
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]);

            $response = $client->send();
            if (!$response->isSuccess()) {
                throw new WeatherException("API request failed: " . $response->getReasonPhrase());
            }

            $data = json_decode($response->getBody(), true);
            $this->logger->info("Weather data fetched for {$city}");
            return $data;

        } catch (\Exception $e) {
            $this->logger->err("Error fetching weather: " . $e->getMessage());
            throw new WeatherException("Unable to fetch weather data");
        }
    }
}
