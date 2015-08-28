<script>
var addressPoints = [
<?php
	$sqlCount = mysql_query("SELECT * FROM location") or die (mysql_error());
	$count = mysql_num_rows($sqlCount) - 1;
	//echo $count;
	$sqlSelect = mysql_query("SELECT * FROM location LIMIT $count") or die (mysql_error());
	while($row = mysql_fetch_array($sqlSelect)){
		$lat = $row['lat_coordinate'];
		$long = $row['long_coordinate'];
		$area = $row['area_name'];
		$city = $row['city'];
		$add = '"'.$area .','.$city.'"';
		echo '['.$lat.','.$long.','.$add.'],';	
	}
	$sqlLast = mysql_query("SELECT * FROM location ORDER BY location_id DESC LIMIT 1") or die (mysql_error());
	$rowLast = mysql_fetch_assoc($sqlLast);
	$latLast = $rowLast['lat_coordinate'];
	$longLast = $rowLast['long_coordinate'];
	$areaLast = $rowLast['area_name'];
	$cityLast = $rowLast['city'];
	$addLast = '"'.$cityLast .', '.$areaLast.'"';
	echo '['.$latLast.','.$longLast.','.$addLast.']';	
?>
];
</script>