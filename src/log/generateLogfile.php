<?php

$format = "csv";

$date_time = date("d.m.Y H:i:s");
$ip = $_SERVER["REMOTE_ADDR"];
$site = $_SERVER['REQUEST_URI'];
$browser = $_SERVER["HTTP_USER_AGENT"];

$months = array(1 => "Januar", 2 => "Februar", 3 => "Maerz", 4 => "April", 5 => "Mai", 6 => "Juni", 7 => "Juli", 8 => "August", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Dezember");
$month = date("n");
$year = date("y");

$filename = "logs/log_" . $months[$month] . "_$year.$format";

$header = array("Datum", "IP", "Seite", "Browser");
$infos = array($date_time, $ip, $site, $browser);

if($format == "csv") {

  $content= '"' . implode('", "', $infos) . '"';
  
} else { 

  $content = implode("\t", $infos);
  
}

$write_header = !file_exists($filename);

$file = fopen($filename, "a");

if($write_header) {

	if($format == "csv") {

    $header_line = '"' . implode('", "', $header) . '"';
  
	} else {

    $header_line = implode("\t", $header);
  
	}

  fputs($file, $header_line . "\n");
  
}

fputs($file, $content . "\n");

fclose($file);

?>
