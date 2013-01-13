<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Buzz\Browser;
use Buzz\Client\Curl;
use Endurance\Strava\StravaClient;

include("../config/.credentials");

$browser = new Browser(new Curl());
$ride_id = 37249145;

try {
    $client = new StravaClient($browser);
    $client->signIn($email, $password);
  	$map =  $client->getMap($ride_id);
} catch (RuntimeException $exception) {
    print sprintf("Error: %s\n", $exception->getMessage());
}
echo $map;


?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBoMxQ0zLyYjTPpyzsANvb1oXBTVS9YGEw&sensor=true">
    </script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(-34.397, 150.644),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
      }
    </script>
  </head>
  <!-- Disable maps for now <body onload="initialize()">-->
  <body>
    <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>
</html>