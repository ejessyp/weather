<?php
if (!isset($message)) {
    if (isset($content)) {
    $content_array = json_decode($content, true);
    // var_dump($content_array);
    $lat = $content_array['latitude'];
    $lng = $content_array['longitude'];
    // var_dump($weather);
    // Produce $rows0 for 5 days history table
    $rows0 = "";
    foreach ($history as $row) {
        $timestamp = time();
        $today = date('l', $row["dt"]);
        if(date('Ymd', $timestamp) == date('Ymd', $row["dt"])) {
            $today = "Today";
        }
        $rows0 .= "<tr>";
        $rows0 .= "<td><li><b>" . $today . "</b></li><li>" . date('M d', $row["dt"]) . "</li></td>";
        $rows0 .= "<td>" .  htmlentities($row['weather'][0]['description']) . "<img src=http://openweathermap.org/img/wn/{$row['weather'][0]['icon']}@2x.png></td>";
        $rows0 .= "<td><li>" .  htmlentities($row['temp']) . "</li></td>";
        $rows0 .= "<td>" .  htmlentities($row['wind_speed'])  . "</td>";
        $rows0 .= "<td>" .  htmlentities($row['wind_deg'])  . "</td>";
        $rows0 .= "<td>" .  date('H:i', $row['sunrise'])  .  "</td>";
        $rows0 .= "<td>" .  date('H:i', $row['sunset'])  .  "</td>";
        $rows0 .= "<td>" .  htmlentities($row['humidity'])  . "</td>";
        $rows0 .= "</tr>\n";
    }
    // Produce $rows for 7 days forecast table
    $rows = "";
    $daily = $weather['daily'];
    foreach ($daily as $row) {
        $timestamp = time();
        $today = date('l', $row["dt"]);
        if(date('Ymd', $timestamp) == date('Ymd', $row["dt"])) {
            $today = "Today";
        }
        $rows .= "<tr>";
        $rows .= "<td><li><b>" . $today . "</b></li><li>" . date('M d', $row["dt"]) . "</li></td>";
        $rows .= "<td>" . htmlentities($row['weather'][0]['description']) . "<img src=http://openweathermap.org/img/wn/{$row['weather'][0]['icon']}@2x.png></td>";
        $rows .= "<td><li>" .  htmlentities($row['temp']['max']) . "</li><li>" .  htmlentities($row['temp']['min']) . "</li></td>";
        $rows .= "<td>" .  htmlentities($row['wind_speed'])  . "</td>";
        $rows .= "<td>" .  htmlentities($row['wind_deg'])  . "</td>";
        $rows .= "<td>" .  date('H:i', $row['sunrise'])  .  "</td>";
        $rows .= "<td>" .  date('H:i', $row['sunset'])  .  "</td>";
        $rows .= "<td>" .  htmlentities($row['humidity'])  . "</td>";
        $rows .= "</tr>\n";
    }
}
}
?>

<h1>Weather:</h1>
Get the weather forceast 7 days and history 5 days.
<form action="weather" method="get">
    <fieldset>
    <legend>Please input a placename or IP address</legend>
    <p>
        <input class=ip type="text" name="ip" value="194.47.150.9 or stockholm">
        <input type="submit" name="submit" value="Submit">
    </p>
    </fieldset>
</form>
<?php
if (isset($message)) {
    echo "<h2>$message</h2>";
} else {
    if (isset($content)) {
        echo " <div class=resetBtn>
            <a href='weather'>Reset Search</a>
            </div>
            <div class=validResult>
                <h1>Place:</h1>
                <p>  {$content_array['address']}  </p>

                <h1>Geo Location:</h1>
                <p> {$content_array['latitude']}     {$content_array['longitude']} </p>
            </div>";
            echo <<<EOD
            <h1>History</h1>
            <table class=forcast>
            <tr>
            <th>Date</th>
            <th>Weather</th>
            <th>Temp(max/min)</th>
            <th>Wind(m/s)</th>
            <th>Wind Degree</th>
            <th>Sunrise</th>
            <th>Sunset</th>
            <th>Humidity</th>
            </tr>
            $rows0
            </table>
    EOD;
        echo <<<EOD
        <h1>Forecast</h1>
        <table class=forcast>
        <tr>
        <th>Date</th>
        <th>Weather</th>
        <th>Temp(max/min)</th>
        <th>Wind(m/s)</th>
        <th>Wind Degree</th>
        <th>Sunrise</th>
        <th>Sunset</th>

        <th>Humidity</th>
        </tr>
        $rows
        </table>
    EOD;
    };
}
?>
<div id="osm-map"></div>
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<link href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" rel="stylesheet"/>
<?php
if (isset($content)) {
    echo "<script type=text/javascript>
    var lat = $lat;
    var lng = $lng;";
    include("map.js");
    echo "</script>";
};
?>
<h1>Weather API</h1>
The rest API, at endpint http:/weatherApi will
accept a JSON parameter in the body containing ip-address or placename
and return a raw json formated response for the current and upcoming weather.
The following routers:
<pre>
<code>
GET /weatherApi show how to use API.
POST /weatherApi Validate ip-address or placename by sending one of them in the body
Such as:
{
    "ip": "194.47.150.9",
    "placename" : "karlskrona"
}
</code>
</pre>

<h1>Test with WeatherApi:</h1>
Please input an IP address or a place, not both:
<form action="weatherApi" method="post">
    <fieldset>
    <legend>IP Address or Name:</legend>
    <p>
        <input type="text" name="ip" value="194.47.150.9">  or
        <input type="text" name="placename" value="kalmar">
        <input type="submit" name=submit value=Submit>
    </p>
    </fieldset>
</form>
