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
	$i = 0; /* the $i variable is used to increment the first index in the multi-dimensional array $entry */
	
	while($data = fgetcsv($handle, 3000, ",")){
		//var_dump($data);
		/* the advantage of using a foreach loop is that you can access the key */	
		
		foreach ($data as $key => $value) {
			if ($key == '0') {
				$entry[$i]["year"] = $value;
			}
			if ($key == '10') {
				$entry[$i]["artist"] = $value;
			}
			if ($key == '14') {
				$entry[$i]["album"] = $value;
			}
			if ($key == '16') {
				$entry[$i]["title"] = $value;
			}
		}
		$i++;
	}
	
	echo $entry[34]["artist"] . ' <--- this should be pink<br /><br />';
	
	echo 'this should list the artists and their songs<br /><br />';
	
	for ($u = 1; $u <= 40; $u++) {
		echo $entry[$u]["artist"] . " - " . $entry[$u]["title"] . '<br />';
	}
	
?>

</body>
</html>