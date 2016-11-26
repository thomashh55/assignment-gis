<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <title></title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.css' rel='stylesheet' />
    <style>
        body { margin:0; padding:0; }
        #map { top:0; bottom:0; width:72vw; height: 80vh; border: 1px solid blue; float:left }
		#s0 { height: 20vh; border: 1px solid blue; }
		#s1 { height: 3vh;  }
		#s2 { height: 10vh;  }
		#s3 { height: 26vh; border: 1px solid blue; }
    </style>
</head>
<body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
	$(document.getElementById('1')).click(function(){
		var distance = '';
	    if (document.getElementById('distanceS').value != ''){
		  distance = document.getElementById('distanceS').value * 1.78 * 60 / 4;
		}
		
	    if (document.getElementById('asphalt').checked){
		 $.post("http://localhost:99/serverSide/API.php",
         {
	  		asphalt : document.getElementById('asphalt').checked,
			street : document.getElementById('street').value,
			distanceS : distance
	  	 },
         function(data,status){
			if(data == "wrong streetname"){
			}
			else{
			   map.getSource('maineAsphalt').setData(JSON.parse(data));
			}
         });
		}
		
        $.post("http://localhost:99/serverSide/API.php",
        {
			street : document.getElementById('street').value,
			distanceS : distance
	  	},
        function(data,status){
			if(data == "wrong streetname"){
			   alert("Please put correct street name");
			}
			else{
			   //document.getElementById("para").innerHTML = data;
			   map.getSource('maine').setData(JSON.parse(data));
			}
        });

        $.post("http://localhost:99/serverSide/API.php",
        {
			street : document.getElementById('street').value,
			distanceS : distance,
			forest : "true"
	  	},
        function(data,status){
			if(data == "wrong streetname"){
			}
			else{
			   map.getSource('forest').setData(JSON.parse(data));
			}
        });

        $.post("http://localhost:99/serverSide/API.php",
        {
			street : document.getElementById('street').value,
			distanceS : distance,
			highway : "true"
	  	},
        function(data,status){
			if(data == "wrong streetname"){
			}
			else{
			   map.getSource('highway').setData(JSON.parse(data));
			}
        });

        $.post("http://localhost:99/serverSide/API.php",
        {
			street : document.getElementById('street').value,
			distanceS : distance,
			restaurants : "true"
	  	},
        function(data,status){
			if(data == "wrong streetname"){
			}
			else{
			   map.getSource('restaurants').setData(JSON.parse(data));
			}
        });

        $.post("http://localhost:99/serverSide/API.php",
        {
			street : document.getElementById('street').value,
			distanceS : distance,
			sights : "true"
	  	},
        function(data,status){			
		    if(data == "wrong streetname"){
			}
			else{
		       map.getSource('sights').setData(JSON.parse(data));
			}
        });
		
        $.post("http://localhost:99/serverSide/API.php",
        {
			street : document.getElementById('street').value,
			distanceS : distance,
			highway2 : "true"
	  	},
        function(data,status){			
		    if(data == "wrong streetname"){
			}
			else{
		       map.getSource('closeToHighway').setData(JSON.parse(data));
			}
        });
    });
});


</script>

<div id='map'></div>

<div id='s0'>
	<p>Find runnable tracks for jogging that are not roads for cars, highlight tracks close to highways to avoid because of bad smog air, find restaurants to resfresh yourself during jogging and find nearest tourist attraction</p>
	<p>1. Write name of street you want to start jogging from</p>
    <p>2. Write number of minutes you want to run</p>
</div>

<div id='s1'>    
	<input type="text" id="street" placeholder="Type name of the street">
	
	<input type="text" id="distanceS" placeholder="Type number of minutes">
	
	<input type="checkbox" id="asphalt" value="true">Only asphalt tracks<br>
</div>

<div id='s2'>
	<button id="1" style="height:20px;width:200px">Find tracks</button>
</div>

<div id='s3'>
	<p>&nbsp;LEGEND:</p>
	<p>&nbsp;&nbsp;BLUE is the color of runnable jogging tracks</p>
	<p>&nbsp;&nbsp;DARK BLUE is the color of asphalt jogging tracks</p>
	<p>&nbsp;&nbsp;RED is the color of highway car roads</p>
	<p>&nbsp;&nbsp;PINK is the color of smog contaminated tracks</p>
	<p>&nbsp;&nbsp;HAMBURGER is the symbol for restaurant</p>
	<p>&nbsp;&nbsp;STAR is the symbol of place of attraction</p>
</div>


<script>
mapboxgl.accessToken = 'pk.eyJ1IjoidG9tYXNoaDU1IiwiYSI6ImNpdXd4N2hzbTAwMWkyeXM5N3AxZDV3b2QifQ.u0X_JTKi2BsmbI4wxzFMpg';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v9',
    center: [17.0551062, 48.1604903],
    zoom: 15
});


map.on('load', function () {
    map.addSource('maine',  {
    type: 'geojson',
    data: {
       "type": "FeatureCollection",
       "features": [{
           "type": "Feature",
           "properties": {},
           "geometry": {
               "type": "Point",
               "coordinates": []
           }
       }]
	}
	});
	
	map.addLayer({
        'id': 'streets',
        'type': 'line',
        'source': 'maine',
        'layout': {     
            'line-join': 'round',
            'line-cap': 'round'
        },
        'paint': {
            'line-color': '#4d94ff',
            'line-width': 2
        }
    });
	
	map.addSource('maineAsphalt',  {
    type: 'geojson',
    data: {
       "type": "FeatureCollection",
       "features": [{
           "type": "Feature",
           "properties": {},
           "geometry": {
               "type": "Point",
               "coordinates": []
           }
       }]
	}
	});
	
	map.addLayer({
        'id': 'streetsAsphalt',
        'type': 'line',
        'source': 'maineAsphalt',
        'layout': {     
            'line-join': 'round',
            'line-cap': 'round'
        },
        'paint': {
            'line-color': '#000066',
            'line-width': 3
        }
    });
	
	map.addSource('forest',  {
    type: 'geojson',
    data: {
       "type": "FeatureCollection",
       "features": [{
           "type": "Feature",
           "properties": {},
           "geometry": {
               "type": "Point",
               "coordinates": []
           }
       }]
	}
	});
	
	map.addLayer({
        'id': 'Fstreets',
        'type': 'line',
        'source': 'forest',
        'layout': {     
            'line-join': 'round',
            'line-cap': 'round'
        },
        'paint': {
            'line-color': '#00ff00',
            'line-width': 3
        }
    });
	
	map.addSource('highway',  {
    type: 'geojson',
    data: {
       "type": "FeatureCollection",
       "features": [{
           "type": "Feature",
           "properties": {},
           "geometry": {
               "type": "Point",
               "coordinates": []
           }
       }]
	}
	});
	
	map.addLayer({
        'id': 'Hstreets',
        'type': 'line',
        'source': 'highway',
        'layout': {     
            'line-join': 'round',
            'line-cap': 'round'
        },
        'paint': {
            'line-color': '#ff3300',
            'line-width': 2
        }
    });
	
	map.addSource('closeToHighway',  {
    type: 'geojson',
    data: {
       "type": "FeatureCollection",
       "features": [{
           "type": "Feature",
           "properties": {},
           "geometry": {
               "type": "Point",
               "coordinates": []
           }
       }]
	}
	});
	
	map.addLayer({
        'id': 'CTHstreets',
        'type': 'line',
        'source': 'closeToHighway',
        'layout': {     
            'line-join': 'round',
            'line-cap': 'round'
        },
        'paint': {
            'line-color': '#ff66cc',
            'line-width': 2
        }
    });
	
	map.addSource('restaurants',  {
    type: 'geojson',
    data: {
       "type": "FeatureCollection",
       "features": [{
           "type": "Feature",
           "properties": {},
           "geometry": {
               "type": "Point",
               "coordinates": [0, 0]
           }
       }]
	}
	});
	
	map.addLayer({
        "id": "Rpolygons",
        "type": "symbol",
        "source": "restaurants",     
		"layout": {            
		    "icon-image": "fast-food-15",
            "text-field": "{name}",
            "text-font": ["Open Sans Semibold", "Arial Unicode MS Regular"],
            "text-offset": [0, 1.2],
            "text-anchor": "top"
        },	   
		"filter": ["==", "$type", "Polygon"]
    });
	
	map.addLayer({
        "id": "Rpoints",
        "type": "symbol",
        "source": "restaurants",     
		"layout": {            
		    "icon-image": "fast-food-11",
            "text-field": "{name}",
            "text-font": ["Open Sans Semibold", "Arial Unicode MS Regular"],
            "text-offset": [0, 1.2],
            "text-anchor": "top"
        },	   
		"filter": ["==", "$type", "Point"]
    });
	
	map.addSource('sights',  {
    type: 'geojson',
    data: {
       "type": "FeatureCollection",
       "features": [{
           "type": "Feature",
           "properties": {},
           "geometry": {
               "type": "Point",
               "coordinates": [0, 0]
           }
       }]
	}
	});

	map.addLayer({
        'id': 'Spolygons',
        'type': 'symbol',
        'source': 'sights',   
		"layout": {            
		    "icon-image": "star-15",
            "text-field": "{name}",
            "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
            "text-offset": [0, 1.2],
            "text-anchor": "top"
        },	 
		"filter": ["==", "$type", "Polygon"]
    });
	
	
	map.addLayer({
        'id': 'Spoints',
        'type': 'symbol',
        'source': 'sights', 
		"layout": {            
		    "icon-image": "star-11",
            "text-field": "{name}",
            "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
            "text-offset": [0, 1.2],
            "text-anchor": "top"
        },	 
		"filter": ["==", "$type", "Point"]
    });
});
</script>

</body>
</html>