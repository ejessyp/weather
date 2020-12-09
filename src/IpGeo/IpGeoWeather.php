<?php

namespace Anax\IpGeo;
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;


/**
 * A model class retrievieng data from an external server.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class IpGeoWeather implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
    // load the config file of key
    private $apikey = "";

    public function setKey($key) : void
    {
        $this->apikey = $key;
    }


    public function getJson(string $ipAdd)
    {
        $url = "http://api.ipstack.com/$ipAdd?access_key={$this->apikey}&hostname=1";
        $res = file_get_contents($url);
        $ipinfo = json_decode($res, true);
        $lat = $ipinfo["latitude"];
        $lng =  $ipinfo["longitude"];
        $ipinfo['link'] = "https://www.openstreetmap.org/?mlat=$lat&mlon=$lng#map=15/$lat/$lng";
        // var_dump($ipinfo);
        return $ipinfo;
    }
}
