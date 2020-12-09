<?php

namespace Anax\OpenWeather;


/**
 * A model class retrievieng data from an external server.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class NameToGeo
{

    public function getGeo($place) : array
    {

        $url = "https://nominatim.openstreetmap.org/search.php?q=$place&polygon_geojson=1&format=jsonv2";
        $opts = array('http'=>array('header'=>"User-Agent: QingPanCleverAddressScript 3.7.6\r\n"));
        $context = stream_context_create($opts);
        // Open the file using the HTTP headers set above
        $file = file_get_contents($url, false, $context);


        $res = json_decode($file);
        //var_dump($res);
        return $res;
    }
}
