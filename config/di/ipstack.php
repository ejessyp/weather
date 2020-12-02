<?php
/**
 * Configuration file for DI container.
 */
return [

    // Services to add to the container.
    "services" => [
        "ipgeoweather" => [
            // Is the service shared, true or false
            // Optional, default is true
            "shared" => true,

            // Is the service activated by default, true or false
            // Optional, default is false
            "active" => false,

            // Callback executed when service is activated
            // Create the service, load its configuration (if any)
            // and set it up.
            "callback" => function () {
                $ipgeoweather = new \Anax\IpGeo\IpGeoWeather();
                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("ipstack.php");
                $settings = $config["config"] ?? null;

                if ($settings["apikey"] ?? null) {
                    $ipgeoweather->setKey($settings["apikey"]);
                }

                return $ipgeoweather;
            }
        ],
    ],
];
