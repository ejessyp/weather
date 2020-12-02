<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Anax\OpenWeather\NameToGeo;

/**
 * Testclass.
 */
class WeatherApiControllerTest extends TestCase
{
    // Create the di container.
    protected $di;


    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $this->di = $di;
    }

    public function testIndexActionGet()
    {
        // Setup the controller
        $controller = new WeatherApiController();
        $controller->setDI($this->di);

        // Test the controller action
        $res = $controller->indexActionGet();
        $res = json_encode($res);
        $this->assertStringContainsString("weatherApi", $res);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionPost()
    {
        // Setup the controller
        $controller = new WeatherApiController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");
        $request->setBody("ip=&placename=kalmar");
        // Test the controller action
        $res = $controller->IndexActionPost();
        // var_dump($res);
        // $body = $res->getBody();
        $this->assertArrayHasKey("forecast", $res[0]);
        $request->setBody("ip=194.47.150.9&placename=");
        // Test the controller action
        $res = $controller->IndexActionPost();
        // $body = $res->getBody();
        $this->assertArrayHasKey("content", $res[0]);
    }
}
