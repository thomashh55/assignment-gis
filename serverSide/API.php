<?php
error_reporting(E_ALL);

$db = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=itris'); 

$validatepass = true;
if($_POST["street"] != ''){
	$query = "select b.way from planet_osm_line b where b.name = '" . $_POST["street"] . "'";
	$result = pg_query($query); 
	$nrows = pg_numrows($result);
	
	if($nrows == 0){
		$validatepass = false;
		echo "wrong streetname";
	}
}

if($validatepass){
	$query = "select b.way from planet_osm_line b where b.highway = 'footway'";

	if($_POST["asphalt"] == "true"){
		$query = $query . " and b.surface = 'asphalt'";
	}

	if(($_POST["street"] != '')and($_POST["distanceS"] != '')){
		$query = "select a.way from planet_osm_line p join (" . $query . ") as a on ST_Dwithin(Geography(a.way),Geography(p.way)," . $_POST["distanceS"] . ") where p.name = '" . $_POST["street"] . "'";
	}

	if($_POST["forest"] == "true"){
		$query = "select ST_Intersection(b.way,a.way) as way from planet_osm_polygon b join (" . $query . ") as a on ST_Crosses(b.way,a.way) where b.landuse = 'forest'";
	}

	if($_POST["highway"] == "true"){
		$query = "select b.way from planet_osm_line b where b.highway = 'primary'";
		$dist = $_POST["distanceS"] + $_POST["distanceS"] * 0.6;
	
		if(($_POST["street"] != '')and($_POST["distanceS"] != '')){
			$query = "select a.way from planet_osm_line p join (" . $query . ") as a on ST_Dwithin(Geography(a.way),Geography(p.way)," . $dist . ") where p.name = '" . $_POST["street"] . "'";
		}
	}
	
	if($_POST["highway2"] == "true"){
		$query2 = "select b.way from planet_osm_line b where b.highway = 'primary'";
		$dist = $_POST["distanceS"] + $_POST["distanceS"] * 0.6;
	
		if(($_POST["street"] != '')and($_POST["distanceS"] != '')){
			$query2 = "select a.way from planet_osm_line p join (" . $query2 . ") as a on ST_Dwithin(Geography(a.way),Geography(p.way)," . $dist . ") where p.name = '" . $_POST["street"] . "'";
		}
		
		$query = "select a.way from (" . $query . ") as a join (" . $query2 . ") as b on ST_Dwithin(Geography(a.way),Geography(b.way),20)";
	}

	if($_POST["restaurants"] == "true"){
		$query = "select b.way, b.name from planet_osm_polygon b where b.amenity  = 'restaurant' union all select p.way, p.name from planet_osm_point p where p.amenity = 'restaurant'";
		$dist = $_POST["distanceS"] + $_POST["distanceS"] * 0.2;
		
		if(($_POST["street"] != '')and($_POST["distanceS"] != '')){
			$query = "select a.way, a.name from planet_osm_line p join (" . $query . ") as a on ST_Dwithin(Geography(a.way),Geography(p.way)," . $dist . ") where p.name = '" . $_POST["street"] . "'";
		}
	}

	if($_POST["sights"] == "true"){
		$query = "select p.way, p.name from planet_osm_point p where p.historic <> ''  union select b.way, b.name from planet_osm_polygon b where b.historic <> ''";
		
		if($_POST["street"] != ''){
			$query = "select a.way, a.name from planet_osm_line l cross join (" . $query . ") as a where l.name = '" . $_POST["street"] . "' order by ST_Distance(a.way,l.way) limit 1";
		}
	}

	if(($_POST["restaurants"] == "true")or($_POST["sights"] == "true")){
		$query = "select ST_AsGeoJSON(x.way) as res, x.name as name from (" . $query . ") as x where x.name <> '' ";		
	}
	else{
		$query = "select ST_AsGeoJSON(x.way) as res from (" . $query . ") as x";
	}
	
	$result = pg_query($query); 
	$myrow = pg_fetch_assoc($result);
	$nrows = pg_numrows($result);
    
	for ($x = 0; $x < $nrows; $x++) {
		$row = pg_fetch_assoc($result,$x);

		if($x == 0){
			print('{  "type": "FeatureCollection", "features": [ ');
		}
	
		if($x >= 0){
			print('{ "type": "Feature", "geometry": ');
			echo $row["res"]; 
			print(', "properties": {');
			
			if(($_POST["restaurants"] == "true")or($_POST["sights"] == "true")){
				echo '"name": ';
				echo '"';
				$vysl = $row["name"]; 
				$vysl = str_replace("\"","\\\"",$vysl);
				echo $vysl;
				echo '"';
			}
			
			print('} }');
		}

		if(($x >= 0)and($x != ($nrows-1))){
			print(',');
		}

		if($x == ($nrows-1)){	
			print(']}');
		}
	} 
}
?>