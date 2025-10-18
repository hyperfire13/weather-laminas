<?php
namespace Weather\Service;

class LocationService
{
    private array $locations = [
        'Mansissla', 'Cebu', 'Davao', 'Baguio', 'Iloilo', 
        'Tagaytay', 'Bacolod', 'Zamboanga', 'Cagayan de Oro', 
        'General Santos', 'Butuan', 'Iligan', 'Cotabato',
        'Puerto Princesa', 'Legazpi'
    ];

    public function getLocations(int $page = 1, int $limit = 3): array
    {
        $offset = ($page - 1) * $limit;
        return array_slice($this->locations, $offset, $limit);
    }

    public function getTotalPages(int $limit = 5): int
    {
        return ceil(count($this->locations) / $limit);
    }

    public function getTotalLocations(): int
    {
        return count($this->locations);
    }
}