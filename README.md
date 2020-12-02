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

You can review the module configuration file in the directory `vendor/bthpan/weather/config`. It consists of the following parts.

| File | Description |
|------|-------------|
| `src/Controller/weatherController.php` | Add "weather" as a di service to make it easy to use from the controller, this is implemented by the model class `weather`. |
| `src/Controller/weatherApiController.php` | Add "weather" as a di service to make it easy to use from the controller, this is implemented by the model class `weather`. |
| `router/510_weather-controller.php` | The routes supported for the weather service. The route is implemented by the `weatherController` class. |
| `router/511_weather-api-controller.php` |The routes supported for the weather API. The route is implemented by the `weatherApiController` class. |
| `weather/README.md` | Short explanation on how to add new datasets. |

You may copy all the module files with the following command.

```
# Move to the root of the course repo
rsync -av  vendor/bthpan/weather/config/ me/redovisa/
rsync -av  vendor/bthpan/weather/src/ me/redovisa/
rsync -av  vendor/bthpan/weather/view/ me/redovisa/
rsync -av  vendor/bthpan/weather/test/ me/redovisa/
```

The weather service is now active on the route `weather/`.
The weather API is now active on the route `weatherApi`.


Dependency
------------------

This is a Anax modulen and its usage is primarly intended to be together with the Anax framework.

You can install an instance on [anax/anax](https://github.com/canax/anax) and run this module inside it, to try it out for test and development.
