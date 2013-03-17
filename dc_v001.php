<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php

/* Displays the contents of the file Billboard(1890-2011).csv, found and adapted from PHP.net */

	$url = "Billboard_short.csv";
	$row = 1;
	
	if (($handle = fopen($url, "r")) !== FALSE) {
    	while (($data = fgetcsv($handle, 3000, ",")) !== FALSE) {
		
			$num = count($data);
			echo "<p> $num fields in line $row: <br /></p>\n";
			$row++;
			
			for ($c=0; $c < $num; $c++) {
				echo $data[$c] . " / ";
			}
		}
	}

?>
</body>
</html>