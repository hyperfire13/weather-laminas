<?php
declare(strict_types=1);

namespace WeatherTest\Integration;

use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

final class IntegrationTest extends AbstractHttpControllerTestCase
{
    protected function setUp(): void
    {
        $this->setApplicationConfig(include __DIR__ . '/../../../config/application.config.php');
        parent::setUp();
    }

    public function testWeatherRouteIsAccessible(): void
    {
        $this->dispatch('/weather');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Weather');
        $this->assertControllerName(\Weather\Controller\WeatherController::class);
        $this->assertMatchedRouteName('weather');
    }

    public function testWeatherPageDisplaysTitle(): void
    {
        $this->dispatch('/weather');
        $this->assertQueryContentContains('h1', 'Current Weather');
    }
}
