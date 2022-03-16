<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Alsok</title>
     <link rel="stylesheet" href="assets/css/style-user.css?v=11" />
     <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo $this->Url->build('/', true); ?>img/favicon.ico">
     <script async defer src="//maps.googleapis.com/maps/api/js?key=<?=MAP_KEY?>&callback=initializeMap"></script>
     <script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
     <style>
          /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
          #map_canvas {
               height: 100%;
          }

          /* Optional: Makes the sample page fill the window. */
          html,
          body {
               height: 100%;
               margin: 0;
               padding: 0;
          }
     </style>
</head>

<body onload="initializeMap()">
     <div id="map_canvas"></div>
     <input type="hidden" value="<?php echo $lat; ?>" id="lat">
        <input type="hidden" value="<?php echo $long; ?>" id="long">
</body>

<script>
    function initializeMap() {
        const myLatLng = { lat: Number($('#lat').val()), lng: Number($('#long').val()) };
        const map = new google.maps.Map(document.getElementById("map_canvas"), {
            zoom: 16,
            center: myLatLng,
        });
        new google.maps.Marker({
            position: myLatLng,
            map,
            title: "You are here!",
        });
    }
</script>
