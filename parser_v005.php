<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Billboard Parser</title>

<style>

	.paragraph {clear:both; width:100%; min-width:1024px;}
	.search {float:left; padding:5px 20px 5px 0;}
	.year {float:left; padding:5px 20px 5px 5px;}
	hr {clear:both;}

</style>


</head>

<body>
<?php

	$url = "Billboard(1890-2011).csv"; /* changed csv to have a smaller data set so that it wouldn't crash as much */
	$handle = fopen($url, "r");
	$entry = array(array("year", "artist", "album", "title", "yearlyRank", "prefix", "artist-i", "featured", "high", "time", "label", "written"));
	$i = 0; /* the $i variable is used to increment the first key in the multi-dimensional array $entry */
	
	while($data = fgetcsv($handle, 10000, ",")){
		
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
	<div class="paragraph">
    	<p>This will perform a search based on title, artist, or album. If no category is selected it will search through all of the categories. This search is case insensitive, but does not work if you do not type the exact name. A successful search will list the Artist, Song title, Album, Year, Record Label, and Length of the song.</p>
        <p>This version is working with a slightly larger set of 366 songs. Try searching train, stereo love, grenade, or any other artist, title, or album found in <a href="http://www.geoffreychan.com/billboard3w03/Billboard_short2.csv" target="_blank">the slightly larger set (but still relatively small)</a>. I have also added the search by year function.
    </div>
	<form method="post">
    <div class="search">
    	<input type="text" name="query" /> <input type="submit" value="Search" /><br />
    	Search by: <input type="radio" name="searchBy" value="title" /> Title <input type="radio" name="searchBy" value="artist" /> Artist <input type="radio" name="searchBy" value="album" /> Album
    </div>
    <div class="year">
    	Limit search by year:<br />
    	<select name="year">
        	<option value="">All years</option>
        	<?php
				$the_years = array();
				
				for ($y = 1; $y <= 123; $y++) {
					$the_years[$y] = $y + 1889; // saves each year into an array that stores the years in indices 1-121 as years 1890-2011
				}
				rsort($the_years); // reverses the year order so that 2011 comes first			
				for ($y = 1; $y <= 123; $y++) {
					echo "<option value=\"" . $the_years[$y] . "\">" . $the_years[$y] . "</option>"; // echos each of the years of form select options so I don't have to type each one out forever
				}
			?>
        </select>
    </div>
    </form>
    <hr />

<?php 

	$query = $_POST['query'];
	$query = str_replace("\\", "", $query); // makes apostrophes searchable
	$searchBy = $_POST['searchBy'];
	$year = $_POST['year'];
	$count = 0;
	$year_count = 0;
	
	if ($query) {
		if ($searchBy == 'title') {
			for ($t = 1; $t <= 366; $t++) {
				$compare = strcasecmp($query, $entry[$t]['title']); // had to use this function so that it does a case insensitive compare
				if ($compare == 0) {
					if ($year != NULL) {
						if ($year == $entry[$t]['year']) {
							echo $entry[$t]['artist'] . " - <b>" . $entry[$t]['title'] . "</b>; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 366) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo $entry[$t]['artist'] . " - <b>" . $entry[$t]['title'] . "</b>; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
					}
				}
				else {
					$count++;
				}
				if ($count == 366) {
					echo "No results for search of '$query'";
				}
			}
		}
		else if ($searchBy == 'artist') {
			for ($t = 1; $t <= 366; $t++) {
				$compare = strcasecmp($query, $entry[$t]['artist']); // does a case insensitive compare
				if ($compare == 0) {
					if ($year != NULL) {
						if ($year == $entry[$t]['year']) {
							echo "<b>" . $entry[$t]['artist'] . "</b> - " . $entry[$t]['title'] . "; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 366) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo "<b>" . $entry[$t]['artist'] . "</b> - " . $entry[$t]['title'] . "; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
					}
				}
				else {
					$count++;
				}
				if ($count == 366) {
					echo "No results for search of '$query'";
				}
			}
		}
		else if ($searchBy == 'album') {
			for ($t = 1; $t <= 366; $t++) {
				$compare = strcasecmp($query, $entry[$t]['album']); // does a case insensitive compare
				if ($compare == 0) {
					if ($year != NULL) {
						if ($year == $entry[$t]['year']) {
							echo $entry[$t]['artist'] . " - " . $entry[$t]['title'] . "; <b>" . $entry[$t]['album'] . "</b>, " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
							}
						else {
							$year_count++;
						}
						if ($year_count == 366) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo $entry[$t]['artist'] . " - " . $entry[$t]['title'] . "; <b>" . $entry[$t]['album'] . "</b>, " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
					}
				}
				else {
					$count++;
				}
				if ($count == 366) {
					echo "No results for search of '$query'";
				}
			}
		}
		else { //searches all 3 categories using the case insensitive function
			for ($t = 1; $t <= 366; $t++) {
				$compare_title = strcasecmp($query, $entry[$t]['title']);
				$compare_artist = strcasecmp($query, $entry[$t]['artist']); 
				$compare_album = strcasecmp($query, $entry[$t]['album']);
				
				//I used 3 if statements so that I could bold the query that was found. I'm sure there is probably an easier way to do this though.
				if ($compare_title == 0) {
					if ($year != NULL) {
						if ($year == $entry[$t]['year']) {
							echo $entry[$t]['artist'] . " - <b>" . $entry[$t]['title'] . "</b>; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 366) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo $entry[$t]['artist'] . " - <b>" . $entry[$t]['title'] . "</b>; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
					}
				}
				if ($compare_artist == 0) {
					if ($year != NULL) {
						if ($year == $entry[$t]['year']) {
							echo "<b>" . $entry[$t]['artist'] . "</b> - " . $entry[$t]['title'] . "; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 366) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo "<b>" . $entry[$t]['artist'] . "</b> - " . $entry[$t]['title'] . "; " . $entry[$t]['album'] . ", " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
					}
				}
				if ($compare_album == 0) {
					if ($year != NULL) {
						if ($year == $entry[$t]['year']) {
							echo $entry[$t]['artist'] . " - " . $entry[$t]['title'] . "; <b>" . $entry[$t]['album'] . "</b>, " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 366) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo $entry[$t]['artist'] . " - " . $entry[$t]['title'] . "; <b>" . $entry[$t]['album'] . "</b>, " . $entry[$t]['year'] . "; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
					}
				}
				else if ($compare_title != 0 & $compare_artist != 0 & $compare_album != 0){
					$count++;
				}
				if ($count == 366) {
					echo "No results for search of '" . $query . "'";
				}
			}
		}
	}
	else if ($query == NULL & $year != NULL) {
		for ($t = 1; $t <= 366; $t++) {
			if ($year == $entry[$t]['year']) {
				echo $entry[$t]['artist'] . " - " . $entry[$t]['title'] . "; " . $entry[$t]['album'] . ", <b>" . $entry[$t]['year'] . "</b>; " . $entry[$t]['label'] . ", " . $entry[$t]['time'] . "<br />";
			}
		}
	}
	
	
	
?>

</body>
</html>