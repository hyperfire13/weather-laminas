<?php
namespace Weather;

use Laminas\Router\Http\Literal;
use Weather\Controller\WeatherController;
use Weather\Service\WeatherService;
use Weather\Service\LocationService;

return [
    'controllers' => [
        'factories' => [
            WeatherController::class => function ($container) {
                return new WeatherController(
                    $container->get(WeatherService::class),
                    $container->get(LocationService::class)
                );
            },
        ],
    ],

    'service_manager' => [
        'factories' => [
            WeatherService::class => function ($container) {
                $apiKey = '7d30a0f5333c7e71dd25699a242a6d69';

                return new WeatherService($apiKey);
            },

            LocationService::class => function ($container) {
                return new LocationService();
            },
        ],
    ],

    'router' => [
        'routes' => [
            'weather' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/weather',
                    'defaults' => [
                        'controller' => WeatherController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
];
