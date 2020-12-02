<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\OpenWeather\NameToGeo;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $request     = $this->di->get("request");
        // var_dump($request->getGet('ip'));
        $submit = $request->getGet('submit');
        $ipAdd = $request->getGet('ip');
        // var_dump($ipAdd);
        if ($submit) {
            //Check if it is ip or a placename
            if (preg_match('/[0-9.]/', $ipAdd)) {
                // Check if it is valid ipv4 or ipv6
                if (filter_var($ipAdd, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) or
                    filter_var($ipAdd, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        $ipgeoweather = $this->di->get("ipgeoweather");
                        $ipjson = $ipgeoweather->getJson($ipAdd);
                        $lat = $ipjson["latitude"];
                        $lon = $ipjson["longitude"];
                        $arr =array($ipjson["country_name"], $ipjson["region_name"], $ipjson["city"]);
                        $full_add = implode("->", $arr);
                } else {
                    $message = "IP format not valid.";
                }
            } else {
                // find geo location from nominatim by placename
                $geolocaion = new NameToGeo();
                $ipjson = $geolocaion -> getGeo($ipAdd);
                if (count($ipjson) == 0) {
                    $message = "Could not find the place.";
                } else {
                $lat = $ipjson[0] ->lat;
                $lon = $ipjson[0] ->lon;
                $full_add = $ipjson[0]->display_name;
                }
            }
            if (!empty($message)) {
                $data = [
                    "message" => $message,
                ];
            } else {
                $ipArr = array("address"=> $full_add, "latitude"=>$lat, "longitude"=>$lon);
                // Generate unixtime for 5 days history
                $fiveDays = [];
                for ($i=1; $i < 6; $i++) {
                    $fiveDays[$i] = strtotime("-$i day");
                }
                // Get data from openweather 5 days history and 7 days forecast
                $openweather = $this->di->get("openweather");
                $weather = $openweather->getCurl($lat, $lon);
                $history = $openweather->getHistoryMCurl($lat, $lon, $fiveDays);

                $data = [
                    "content" => json_encode($ipArr, JSON_PRETTY_PRINT),
                    "weather" => $weather,
                    "history" => $history,
                ];
            }

            $page->add("weather", $data);
            return $page->render([
                "title" => "Weather",
            ]);
        } else {
            $page->add("weather");
            return $page->render([
                "title" => "Weather",
            ]);
        }
    }
}
