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
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBM2d7XoPYtTcV9cWm-_IJc8B01pHNObxY&sensor=true&language=fa">
    </script>
    <script type="text/javascript">
      function initialize() {
        var myOptions = {
          center: new google.maps.LatLng(35.66, 51.434),
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
		google.maps.event.addListener(map, 'click', function(event) {
			    placeMarker(event.latLng,map);
			  });
      }
	  function placeMarker(position, map) {
			alert(position);
	        var marker = new google.maps.Marker({
	          position: position,
	          map: map
	        });
	        map.panTo(position);
	      }
    </script>
  </head>
  <body onload="initialize()">
    <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>
</html>