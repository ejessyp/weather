<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Testclass.
 */
class WeatherControllerTest extends TestCase
{
    // Create the di container.
    // protected $di;


    /**
     * Prepare before each test.
     */
    protected function setUp()
    {

        // Setup di
        $this->di = new DIFactoryConfig();

        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        // Use a different cache dir for unit test
        // $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");


        // $this->di = $di;
    }



    /**
     * Test the route "index".
     */
    public function testIndexActionGet()
    {
        // Setup the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);

        $request     = $this->di->get("request");
        $request->setGet("submit", "Submit");
        $request->setGet("ip", "194.47.150.9");
        // var_dump($controller);

        // Test the controller action
        $res = $controller->indexActionGet();

        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        $request->setGet("ip", "stockholm");
        $res = $controller->indexActionGet();
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        $request->setGet("submit", "");
        $res = $controller->indexActionGet();
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

    }
}
