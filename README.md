[![Build Status](https://travis-ci.com/ejessyp/weather.svg?branch=main)](https://travis-ci.com/ejessyp/weather)
Weather module implements a weather service.

Install as an Anax module
------------------------------------

There are two steps in the installation procedure, 1) first install the module using composer and then 2) integrate it into you Anax base installation.



### Step 1, install using composer.

Install the module using composer.

```
composer require bthpan/weather
```

### Step 2, integrate into your Anax base

You can review the module files in the directory `vendor/bthpan/weather/`. It consists of the following parts.

| File | Description |
|------|-------------|
| `src/Controller/weatherController.php` | This is a controller class,"weather" service to show 5 days history and 7 days forecast. |
| `src/Controller/weatherApiController.php` |This is a controller class,"weatherApi" return the same data like the above but with json format. |
| `src/IpGeo/IpGeoweather.php` | This is a module class, return data from api.ipstack.com with json format. |
| `src/OpenWeather/OpenWeather.php` | This is a module class, return data from api.openweathermap.org with json format. |
| `src/OpenWeather/NameToGeo.php` | This is a module class, return data from nominatim.openstreetmap.org with json format. |
| `router/450_weather-controller.php` | The routes supported for the weather service. The route is implemented by the `weatherController` class. |
| `router/451_weather-api-controller.php` |The routes supported for the weather API. The route is implemented by the `weatherApiController` class. |
| `weather/README.md` | Short explanation on how to add new datasets. |

You may copy all the module files with the following command.

```
# Move to dir me/redovisa
rsync -av  vendor/bthpan/weather/config/router/  ./config/router/
# Copy the files for creating the services into $di for ipgeoweather and openweather
rsync -av  vendor/bthpan/weather/test/config/di/ipstack.php  ./config/di/
rsync -av  vendor/bthpan/weather/test/config/di/openweather.php  ./config/di/
# Copy the config files(apikey) for creating the services into $di for ipgeoweather and openweather
rsync -av  vendor/bthpan/weather/test/config/ipstack.php ./config/
rsync -av  vendor/bthpan/weather/test/config/weather.php  ./config/
# Copy the view files
rsync -av  vendor/bthpan/weather/view/ ./view
```

The weather service is now active on the route `weather2/`.
The weather API is now active on the route `weather2Api`.


Dependency
------------------

This is a Anax modulen and its usage is primarly intended to be together with the Anax framework.

You can install an instance on [anax/anax](https://github.com/canax/anax) and run this module inside it, to try it out for test and development.
