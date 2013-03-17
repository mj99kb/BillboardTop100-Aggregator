<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex" />
<title>Billboard Parser</title>

<style>

	.paragraph {clear:both; width:100%; min-width:1024px;}
	.search {float:left; padding:5px 10px 5px 0;}
	.year {float:left; padding:5px 20px 5px 5px;}
	.aggregation {float:left; padding:5px 20px 5px 20px; border-left:1px solid #999;}
	hr {clear:both;}
	textarea {width:1024px; min-height:500px; height:auto;}
	.listArtists {float:left; padding-top:10px; font-size:12px;}
	.listAlbums {float:left; padding-left:20px; padding-top:10px; font-size:12px;}
	.listLabels {float:left; padding-left:20px; padding-top:10px; font-size:12px;}

</style>


</head>

<body>
<?php

	ini_set('memory_limit','300M');
	set_time_limit(0);
	$url = "Billboard(1890-2011).csv"; /* changed csv to have a smaller data set so that it wouldn't crash as much */
	$handle = fopen($url, "r");
	$c = 0; /* the $i variable is used to increment the first key in the multi-dimensional array $entries */
	
	while($data = fgetcsv($handle, 30000, ",")){
		
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
		//this for loop saves the weekly rank from weeks 1-76 in keys 1-76 (wasn't using it so I greyed it out to speed up the loading time)
		/*for ($i = 1; $i <= 76; $i++) {
			$entries[$c][$i] = $data[$i+33];
		}
		$c++;*/
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
    <form method="post">
    <div class="aggregation">
    	Aggregate Data:<br />
        <select name="aggregate">
        	<option value="allArtists">List All Artists</option>
            <option value="allTitles">List All Songs</option>
            <option value="allAlbums">List All Albums</option>
            <option value="allLabels">List All Record Labels</option>
            <option value="frequency">Frequency Stats</option>
		</select>
        <input type="submit" value="Go" />
    </div>
    </form>
    <hr />
    
<?php
	//SEARCH
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
	//END SEARCH
?>

<form name="select_all">
<?php
	//AGGREGATE DATA
	$aggregate = $_POST['aggregate'];
	
	if ($aggregate == "allArtists") {
		echo "This lists all of the artists to ever make the top 100 billboard charts from 1890 - 2011. The artists are seperated by commas. Duplicates are not removed, so if an artist has appeared on the top 100 list more than once their name will be listed more than once.<br /><input type=\"button\" value=\"Highlight Text\" onClick=\"javascript:this.form.text_area.focus();this.form.text_area.select();\"><br />--------------------------------<br /><textarea name=\"text_area\">";
		for ($c = 1; $c <= 38317; $c++) {
			echo $entries[$c]['artist'] . ",";
		}
	}
	if ($aggregate == "allTitles") {
		echo "This lists all of the songs to ever make the top 100 billboard charts from 1890 - 2011. The songs are seperated by commas.<br /><input type=\"button\" value=\"Highlight Text\" onClick=\"javascript:this.form.text_area.focus();this.form.text_area.select();\"><br />--------------------------------<br /><textarea name=\"text_area\">";
		for ($c = 1; $c <= 38317; $c++) {
			echo $entries[$c]['title'] . ",";
		}
	}
	if ($aggregate == "allAlbums") {
		echo "This lists all of the albums to ever make the top 100 billboard charts from 1890 - 2011. The albums are seperated by commas. Duplicates are not removed, so if an album has had more than one song on the top 100 billboard charts, it will be listed more than once.<br /><br />Some of the fields have 's' in place of an album name. I have looked through the notes in the spreadsheet and searched online, but I have not found out what this means. Also, some songs do not have albums so their fields are blank.<br /><input type=\"button\" value=\"Highlight Text\" onClick=\"javascript:this.form.text_area.focus();this.form.text_area.select();\"><br />--------------------------------<br /><textarea name=\"text_area\">";
		for ($c = 1; $c <= 38317; $c++) {
			echo $entries[$c]['album'] . ",";
		}
	}
	if ($aggregate == "allLabels") {
		echo "This lists all of the record labels to ever make the top 100 billboard charts from 1890 - 2011. The labels are seperated by commas. Duplicates are not removed, so if label has had more than one song on the top 100 billboard charts, it will be listed more than once. Some labels also contain a label number.<br /><input type=\"button\" value=\"Highlight Text\" onClick=\"javascript:this.form.text_area.focus();this.form.text_area.select();\"><br />--------------------------------<br /><textarea name=\"text_area\">";
		for ($c = 1; $c <= 38317; $c++) {
			echo $entries[$c]['label'] . ",";
		}
	}
	echo "</textarea></form>";
	
	//FREQUENCY STATS
	if ($aggregate == "frequency") {
		echo "Each list will display the top 100 highiest frequency entries of each category as well as the amount of times they appear on the top 100 Billboard charts from 1890 - 2011.";
		//Calculate the top 100 artists
		for ($c = 1; $c <= 38317; $c++) {
			$artists[$c] = $entries[$c]['artist'];
		}
		$count_artists = array_count_values($artists);
		arsort($count_artists); //you can't use rsort() because it will change the keys into numbers, while arsort maintains the index/value association
		
		echo "<div class=\"listArtists\"><b>The top 100 artists on the Billboard Top 100</b><br />";
		
		$num_artists = 1;
		foreach ($count_artists as $key => $value) {
			if ($num_artists < 101) {
				echo $num_artists . ") " . $key . " - <i>" . $value . "</i><br />";
				$num_artists++;
			}
		}
		echo "</div>";
		
		//Calculate the top 100 albums
		for ($c = 1; $c <= 38317; $c++) {
			$albums[$c] = $entries[$c]['album'];
		}
		$count_albums = array_count_values($albums);
		arsort($count_albums); //you can't use rsort() because it will change the keys into numbers, while arsort maintains the index/value association
		
		echo "<div class=\"listAlbums\"><b>The top 100 albums on the Billboard Top 100</b><br />";
		
		$num_albums = 1;
		foreach ($count_albums as $key => $value) {
			if ($num_albums < 101) {
				if ($key != "" & $key != "s" & $key != "Single" & $key != "single" & $key != "unk") { //got rid of blanks, singles, unknowns, and the 's' (I don't know what it represents)
					echo $num_albums . ") " . $key . " - <i>" . $value . "</i><br />";
					$num_albums++;
				}
			}
		}
		echo "</div>";
		
		//Calculate the top 100 labels
		for ($c = 1; $c <= 38317; $c++) {
			$labels[$c] = $entries[$c]['label'];
		}
		$count_labels = array_count_values($labels);
		arsort($count_labels); //you can't use rsort() because it will change the keys into numbers, while arsort maintains the index/value association
		
		echo "<div class=\"listLabels\"><b>The top 100 labels on the Billboard Top 100</b><br />";
		
		$num_labels = 1;
		foreach ($count_labels as $key => $value) {
			if ($num_labels < 101) {
				if ($key != "") { //gets rid of blanks
					echo $num_labels . ") " . $key . " - <i>" . $value . "</i><br />";
					$num_labels++;
				}
			}
		}
		echo "</div>";
	}
?>

  
</body>
</html>