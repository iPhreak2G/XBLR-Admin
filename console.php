<?php
include('config.php');
$file = $loglocation;
$f = fopen($file, "r");
while ( $line = fgets($f, 1000) ) {
	if($line != null || $line != '')
		print $line;
}
?>