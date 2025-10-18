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
        
        // Simple file logger
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
                $statusCode = $response->getStatusCode();
                
                
                switch ($statusCode) {
                    case 401:
                        $this->logger->err("Invalid API key for {$city}");
                        throw WeatherException::invalidApiKey();
                    
                    case 404:
                        $this->logger->warn("City not found: {$city}");
                        throw WeatherException::cityNotFound($city);
                    
                    default:
                        $this->logger->err("API error for {$city}: " . $response->getReasonPhrase());
                        throw WeatherException::apiRequestFailed($response->getReasonPhrase());
                }
            }

            $data = json_decode($response->getBody(), true);
            
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->err("Invalid JSON response for {$city}");
                throw WeatherException::apiRequestFailed("Invalid JSON response");
            }
            
            
            if (!isset($data['main']) || !isset($data['weather'])) {
                $this->logger->err("Incomplete weather data for {$city}");
                throw WeatherException::apiRequestFailed("Incomplete weather data");
            }

            $this->logger->info("Weather data fetched successfully for {$city}");
            return $data;

        } catch (WeatherException $e) {
            // Re-throw our custom exceptions
            throw $e;
            
        } catch (\Laminas\Http\Client\Exception\RuntimeException $e) {
       
            $this->logger->err("Network error for {$city}: " . $e->getMessage());
            throw WeatherException::networkError($e->getMessage());
            
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            $this->logger->err("Unexpected error for {$city}: " . $e->getMessage());
            throw WeatherException::apiRequestFailed($e->getMessage());
        }
    }
}