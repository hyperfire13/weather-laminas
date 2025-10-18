<?php
namespace Weather\Exception;

use Exception;

class WeatherException extends Exception
{
    
    public static function apiRequestFailed(string $reason): self
    {
        return new self("Weather API request failed: {$reason}");
    }
    
    public static function cityNotFound(string $city): self
    {
        return new self("City '{$city}' not found");
    }
    
    public static function invalidApiKey(): self
    {
        return new self("Invalid API key provided");
    }
    
    public static function networkError(string $message): self
    {
        return new self("Network error: {$message}");
    }
}