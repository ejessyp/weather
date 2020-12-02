<?php
/**
 * Load the sample json controller.
 */
return [
    "routes" => [
        [
            "info" => "Weather Api Controller.",
            "mount" => "weatherApi",
            "handler" => "\Anax\Controller\WeatherApiController",
        ],
    ]
];
