<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\OpenWeather\NameToGeo;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample JSON controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 */
class WeatherApiController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


    /**
     * This is the index method action, it handles:
     * GET METHOD mountpoint
     * GET METHOD mountpoint/
     * GET METHOD mountpoint/index
     *
     * @return array
     */
    public function indexActionGet() : array
    {
        $json = [
            "message" => "Use POST with IP-address or placename in body to validate",
            "example" => "POST /weatherApi/ {'ip': '8.8.8.8', 'placename': 'karlskrona'}",
        ];
        $page = $this->di->get("page");

        $page->add("anax/v2/plain/pre", [
            "content" => $json,
        ]);
        return [$json];
    }

    public function indexActionPost()
    {
        $data = $this->di->request->getBody();
        // To change %3A to ":" because of http_build_query
        // var_dump($data);
        $array = explode("&", $data);
        $data = urldecode($array[0]);
        $name = substr($array[1], 10);
        $ipAdd = substr($data, 3);

        if ($name) {
            $geolocaion = new NameToGeo();
            $ipjson = $geolocaion -> getGeo($name);
            // var_dump($ipjson);
            $lat = $ipjson[0] ->lat;
            $lon = $ipjson[0] ->lon;
            $full_add = $ipjson[0]->display_name;
        } else {
            $ipgeoweather = $this->di->get("ipgeoweather");
            $ipjson = $ipgeoweather->getJson($ipAdd);
            $lat = $ipjson["latitude"];
            $lon = $ipjson["longitude"];
            $arr =array($ipjson["country_name"], $ipjson["region_name"], $ipjson["city"]);
            $full_add = implode("->", $arr);
        }
        $ipArr = array("address"=> $full_add, "latitude"=>$lat, "longitude"=>$lon);
        $fiveDays = [];
        for ($i=1; $i < 6; $i++) {
            $fiveDays[$i] = strtotime("-$i day");
        }
        $openweather = $this->di->get("openweather");
        $weather = $openweather->getCurl($lat, $lon);
        $history = $openweather->getHistoryMCurl($lat, $lon, $fiveDays);
        $json_data = [
            "content" => $ipArr,
            "forecast" => $weather,
            "history" => $history,
        ];
        return [$json_data];
    }
}
