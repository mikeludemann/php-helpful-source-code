<?php 

$endTime = mktime(18, 15, 0, 12, 31, 2019); 

$actualTime = microtime(true); 

$differenceTime = $endTime - $actualTime; 

$day = floor($differenceTime / (24*3600)); 
$differenceTime = $differenceTime % (24*3600); 
$hours = floor($differenceTime / (60*60)); 
$differenceTime = $differenceTime % (60*60); 
$min = floor($differenceTime / 60); 
$seconds = $differenceTime % 60; 

$secondsText = "";
$minutesText = "";
$hoursText = "";
$daysText = "";

if($seconds == 1){

	$secondsText = " second ";

} else {

	$secondsText = " seconds ";

}

if($minutes == 1){

	$minutesText = " minute ";

} else {

	$minutesText = " minutes ";

}

if($hours == 1){

	$hoursText = " hour ";

} else {

	$hoursText = " hours ";

}

if($day == 1){

	$daysText = " day ";

} else {

	$daysText = " days ";

}

echo $day . $daysText . $hours . $hoursText . $min . $minutesText . $seconds . $secondsText; 

?>
