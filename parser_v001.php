<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Billboard Parser</title>
</head>

<body>
<?php

	$url = "Billboard_short.csv";
	$handle = fopen($url, "r");
	while($data = fgetcsv($handle, 3000, ",")){
		//var_dump($data);
		
		echo "<br /><br />\n";
		//echo count($data);
		foreach ($data as $key => $value) {
			echo "$value , ";
		}
	}

	
?>

</body>
</html>