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
	
?>
	<div>
    	<p>This will perform a search based on title, artist, or album. If no category is selected it will search through all of the categories. This search is case insensitive, but does not work if you do not type the exact name. A successful search will list the Artist, Song title, Album, Year, Record Label, and Length of the song.</p>
        <p>This version is only working with a set of 40 songs. Try searching train, stereo love, grenade, or any other artist, title, or album found in <a href="http://www.geoffreychan.com/billboard3w03/Billboard_short.csv" target="_blank">the smaller set</a>.
    </div>
	<form method="post">
    <div class="search">
    	<input type="text" name="query" /> <input type="submit" value="Search" /><br />
    	Search by: <input type="radio" name="searchBy" value="title" /> Title <input type="radio" name="searchBy" value="artist" /> Artist <input type="radio" name="searchBy" value="album" /> Album
    </div>
    </form>
    <hr />

<?php 

	$query = $_POST['query'];
	$searchBy = $_POST['searchBy'];
	$count = 0;
	
	if ($query) {
		if ($searchBy == 'title') {
			for ($t = 1; $t <= 40; $t++) {
				$compare = strcasecmp($query, $entry[$t]['title']); // had to use this function so that it does a case insensitive compare
				if ($compare == 0) {
					echo $entry[$t]['artist'] . " - <b>" . $entry[$t]['title'] . "</b>; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
				}
				else {
					$count++;
				}
				if ($count == 40) {
					echo "No results for search of '$query'";
				}
			}
		}
		else if ($searchBy == 'artist') {
			for ($t = 1; $t <= 40; $t++) {
				$compare = strcasecmp($query, $entry[$t]['artist']); // does a case insensitive compare
				if ($compare == 0) {
					echo "<b>" . $entry[$t]['artist'] . "</b> - " . $entry[$t]['title'] . "; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
				}
				else {
					$count++;
				}
				if ($count == 40) {
					echo "No results for search of '$query'";
				}
			}
		}
		else if ($searchBy == 'album') {
			for ($t = 1; $t <= 40; $t++) {
				$compare = strcasecmp($query, $entry[$t]['album']); // does a case insensitive compare
				if ($compare == 0) {
					echo $entry[$t]['artist'] . " - " . $entry[$t]['title'] . "; <b>" . $entry[$t]['album'] . "</b>, " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
				}
				else if ($query != $entry[$t]['album']) {
					$count++;
				}
				if ($count == 40) {
					echo "No results for search of '$query'";
				}
			}
		}
		else { //searches all 3 categories using the case insensitive function
			for ($t = 1; $t <= 40; $t++) {
				$compare_title = strcasecmp($query, $entry[$t]['title']);
				$compare_artist = strcasecmp($query, $entry[$t]['artist']); 
				$compare_album = strcasecmp($query, $entry[$t]['album']);
				if ($compare_title == 0 || $compare_artist == 0 || $compare_album == 0) {
					echo $entry[$t]['artist'] . " - " . $entry[$t]['title'] . "; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
				}
				else if ($query != $entry[$t]['album'] || $query != $entry[$t]['title'] || $query != $entry[$t]['artist']) {
					$count++;
				}
				if ($count == 40) {
					echo "No results for search of '$query'";
				}
			}
		}
	}
	
	
	
?>

</body>
</html>