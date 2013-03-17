<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Billboard Parser</title>
</head>

<body>
<?php

	$url = "Billboard_short.csv"; /* changed csv to have a smaller data set so that it wouldn't crash as much */
	$handle = fopen($url, "r");
	$entry = array(array("year", "artist", "album", "title"));
	$i = 0; /* the $i variable is used to increment the first key in the multi-dimensional array $entry */
	
	while($data = fgetcsv($handle, 3000, ",")){
		//var_dump($data);
		/* the advantage of using a foreach loop is that you can access the key */	
		
		foreach ($data as $key => $value) {
			if ($key == 0) {
				$entry[$i]["year"] = $value;
			}
			if ($key == 1) {
				$entry[$i]["yearlyRank"] = $value;
			}
			if ($key == 3) {
				$entry[$i]["prefix"] = $value; // Unique key with year and song list number
			}
			if ($key == 8) {
				$entry[$i]["high"] = $value; // Highest rank  on the top 100
			}
			if ($key == 10) {
				$entry[$i]["artist"] = $value; // Artist of the song
			}
			if ($key == 11) {
				$entry[$i]["artist-i"] = $value; // Artist inverted eg. Last name, First name
			}
			if ($key == 12) {
				$entry[$i]["featured"] = $value; // Featured artists on the song
			}
			if ($key == 14) {
				$entry[$i]["album"] = $value; // Album name
			}
			if ($key == 16) {
				$entry[$i]["title"] = $value; // Title of the song
			}
			if ($key == 17) {
				$entry[$i]["time"] = $value; // Length of the song
			}
			if ($key == 24) {
				$entry[$i]["label"] = $value; // Recording label
			}
			if ($key == 30) {
				$entry[$i]["written"] = $value; // Person(s) who wrote the song
			}
			
			//The logic of this while loop took me a good 4 hours to grasp.. 
			$k = 34; //34 is the rank of the song in the first week it entered the top 100 chart. The variable $k is compared to $key and then increased by 1.
			$week = 1; //The keys 1 - 76 contain the value of each corresponding weeks rank.
			while ($k < 111) {
				if ($key == $k) {
					$entry[$i][$week] = $value;
				}
				$week++;
				$k++;
			}
		}
		$i++;
	}
	
	echo "This should list the weekly rank for " . $entry[3]['artist']. " - " . $entry[3]['title'] . "<br /><br />";
	
	for ($u = 1; $u <= 76; $u++) {
		if ($entry[3][$u] == NULL & $entry[3][$u+1] == NULL & $entry[3][$u+2] == NULL) { // Will stop listing once there are 3 empty weeks. Not a great criteria, but works for this sample.
			break;
		}
		echo "Week " . $u . " - rank " . $entry[3][$u] . "<br />";
	}
	
?>

</body>
</html>