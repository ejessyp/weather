<?php

namespace Anax\OpenWeather;


/**
 * A model class retrievieng data from an external server.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class OpenWeather
{

    // load the config file of key
    private $apikey = "";

    public function setKey($key) : void
    {
        $this->apikey = $key;
    }

    public function getCurl($lat, $lon) : array
    {
        $url = "https://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&units=metric&appid={$this->apikey}";

        //  Initiate curl handler
        $ch = curl_init();

        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);

        // Execute
        $data = curl_exec($ch);

        // Closing
        curl_close($ch);
        // var_dump($data);
        $weather = json_decode($data, true);
        return $weather;
    }


    public function getHistoryMCurl($lat, $lon, $fiveDays) : array
    {
        $url = "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$lat&lon=$lon&units=metric&appid={$this->apikey}";
        // var_dump($fiveDays);
        $options = [
            CURLOPT_RETURNTRANSFER => true,
        ];

        // Add all curl handlers and remember them
        // Initiate the multi curl handler
        $mh = curl_multi_init();
        $chAll = [];
        foreach ($fiveDays as $dt) {
            $ch = curl_init("$url&dt=$dt");
            curl_setopt_array($ch, $options);
            curl_multi_add_handle($mh, $ch);
            $chAll[] = $ch;
        }

        // Execute all queries simultaneously,
        // and continue when all are complete
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        // Close the handles
        foreach ($chAll as $ch) {
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        // All of our requests are done, we can now access the results
        $response = [];
        foreach ($chAll as $ch) {

            $data = curl_multi_getcontent($ch);
            // var_dump(json_decode($data, true));

            $data = json_decode($data, true)['current'];
            $response[] = $data;
        }

        return $response;
    }
}
