<?php
namespace Weather\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Weather\Service\WeatherService;
use Weather\Service\LocationService;
use Weather\Exception\WeatherException;

class WeatherController extends AbstractActionController
{
    private WeatherService $weatherService;
    private LocationService $locationService;

    public function __construct(WeatherService $weatherService, LocationService $locationService)
    {
        $this->weatherService = $weatherService;
        $this->locationService = $locationService;
    }

    public function indexAction()
    {
        $page = (int) $this->params()->fromQuery('page', 1);
        
        $locations = $this->locationService->getLocations($page, 3);
        $totalPages = $this->locationService->getTotalPages(3);
        
        
        $weatherData = [];
        foreach ($locations as $location) {
            try {
                $weatherData[$location] = $this->weatherService->getWeather($location);
            } catch (WeatherException $e) {
                
                $weatherData[$location] = [
                    'error' => true,
                    'message' => $e->getMessage()
                ];
            }
        }

        return new ViewModel([
            'locations' => $locations,
            'weatherData' => $weatherData,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}