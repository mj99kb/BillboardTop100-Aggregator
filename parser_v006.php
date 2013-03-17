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

	ini_set('memory_limit','100M');
	set_time_limit(0);
	$url = "Billboard(1890-2011).csv"; /* changed csv to have a smaller data set so that it wouldn't crash as much */
	$handle = fopen($url, "r");
	$i = 0; /* the $i variable is used to increment the first key in the multi-dimensional array $entries */
	
	while($data = fgetcsv($handle, 10000, ",")){
		
	   $entries[] = array(
		   'year' => $data[0],
		   'yearlyRank' => $data[1],
		   'prefix' => $data[3],
		   'high' => $data[8],
		   'artist' => $data[10],
		   'artist-i' => $data[11],
		   'featured' => $data[12],
		   'album' => $data[14],
		   'title' => $data[16],
		   'time' => $data[17],
		   'label' => $data[24],
		   'written' => $data[30]
	   );
		//var_dump($entries);
	}
	
?>

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
			for ($t = 1; $t <= 38317; $t++) {
				$compare = strcasecmp($query, $entries[$t]['title']); // had to use this function so that it does a case insensitive compare
				if ($compare == 0) {
					if ($year != NULL) {
						if ($year == $entries[$t]['year']) {
							echo $entries[$t]['artist'] . " - <b>" . $entries[$t]['title'] . "</b>; " . $entries[$t]['album'] . ", " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 38317) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo $entries[$t]['artist'] . " - <b>" . $entries[$t]['title'] . "</b>; " . $entries[$t]['album'] . ", " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
					}
				}
				else {
					$count++;
				}
				if ($count == 38317) {
					echo "No results for search of '$query'";
				}
			}
		}
		else if ($searchBy == 'artist') {
			for ($t = 1; $t <= 38317; $t++) {
				$compare = strcasecmp($query, $entries[$t]['artist']); // does a case insensitive compare
				if ($compare == 0) {
					if ($year != NULL) {
						if ($year == $entries[$t]['year']) {
							echo "<b>" . $entries[$t]['artist'] . "</b> - " . $entries[$t]['title'] . "; " . $entries[$t]['album'] . ", " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 38317) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo "<b>" . $entries[$t]['artist'] . "</b> - " . $entries[$t]['title'] . "; " . $entries[$t]['album'] . ", " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
					}
				}
				else {
					$count++;
				}
				if ($count == 38317) {
					echo "No results for search of '$query'";
				}
			}
		}
		else if ($searchBy == 'album') {
			for ($t = 1; $t <= 38317; $t++) {
				$compare = strcasecmp($query, $entries[$t]['album']); // does a case insensitive compare
				if ($compare == 0) {
					if ($year != NULL) {
						if ($year == $entries[$t]['year']) {
							echo $entries[$t]['artist'] . " - " . $entries[$t]['title'] . "; <b>" . $entries[$t]['album'] . "</b>, " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
							}
						else {
							$year_count++;
						}
						if ($year_count == 38317) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo $entries[$t]['artist'] . " - " . $entries[$t]['title'] . "; <b>" . $entries[$t]['album'] . "</b>, " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
					}
				}
				else {
					$count++;
				}
				if ($count == 38317) {
					echo "No results for search of '$query'";
				}
			}
		}
		else { //searches all 3 categories using the case insensitive function
			for ($t = 1; $t <= 38317; $t++) {
				$compare_title = strcasecmp($query, $entries[$t]['title']);
				$compare_artist = strcasecmp($query, $entries[$t]['artist']); 
				$compare_album = strcasecmp($query, $entries[$t]['album']);
				
				//I used 3 if statements so that I could bold the query that was found. I'm sure there is probably an easier way to do this though.
				if ($compare_title == 0) {
					if ($year != NULL) {
						if ($year == $entries[$t]['year']) {
							echo $entries[$t]['artist'] . " - <b>" . $entries[$t]['title'] . "</b>; " . $entries[$t]['album'] . ", " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 38317) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo $entries[$t]['artist'] . " - <b>" . $entries[$t]['title'] . "</b>; " . $entries[$t]['album'] . ", " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
					}
				}
				if ($compare_artist == 0) {
					if ($year != NULL) {
						if ($year == $entries[$t]['year']) {
							echo "<b>" . $entries[$t]['artist'] . "</b> - " . $entries[$t]['title'] . "; " . $entries[$t]['album'] . ", " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 38317) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo "<b>" . $entries[$t]['artist'] . "</b> - " . $entries[$t]['title'] . "; " . $entries[$t]['album'] . ", " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
					}
				}
				if ($compare_album == 0) {
					if ($year != NULL) {
						if ($year == $entries[$t]['year']) {
							echo $entries[$t]['artist'] . " - " . $entries[$t]['title'] . "; <b>" . $entries[$t]['album'] . "</b>, " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
						}
						else {
							$year_count++;
						}
						if ($year_count == 38317) {
							echo "No results for search of '$query' in $year";
						}
					}
					else {
						echo $entries[$t]['artist'] . " - " . $entries[$t]['title'] . "; <b>" . $entries[$t]['album'] . "</b>, " . $entries[$t]['year'] . "; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
					}
				}
				else if ($compare_title != 0 & $compare_artist != 0 & $compare_album != 0){
					$count++;
				}
				if ($count == 38317) {
					echo "No results for search of '" . $query . "'";
				}
			}
		}
	}
	else if ($query == NULL & $year != NULL) {
		for ($t = 1; $t <= 38317; $t++) {
			if ($year == $entries[$t]['year']) {
				echo $entries[$t]['artist'] . " - " . $entries[$t]['title'] . "; " . $entries[$t]['album'] . ", <b>" . $entries[$t]['year'] . "</b>; " . $entries[$t]['label'] . ", " . $entries[$t]['time'] . "<br />";
			}
		}
	}
?>
	    
</body>
</html>