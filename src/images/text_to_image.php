<?php

$font = 'text.ttf';
$ext = substr($imgfile,-3);
$ext = strtolower($ext);

if($ext == "jpg" || $ext == "jpe"){
	
	$image = @imagecreatefromjpeg("$imgfile");

} elseif ($ext == "gif") {
	
	$image = @imagecreatefromgif("$imgfile");

} 
else {

	print "Unknown image format"; 
	
	exit;

}

if (!$image) {

	$image = ImageCreate(150, 30);

	$backgroundColor = ImageColorAllocate($image, 255, 255, 255);

	$textColor = ImageColorAllocate($image, 0, 0, 0);

	ImageFilledRectangle($image, 0, 0, 150, 30, $backgroundColor); 
	
	ImageString($image, 1, 5, 5, "Error loading $imgfile", $textColor); 

	return $image;

}

$x = imagesx($image);
$y = imagesy($image);

$fontsize = $x / 20;
$fontsize = floor($fontsize);

if($fontsize < 10) {

	$fontsize = 10;

}

$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);

imagettftext($image, $fontsize, 0, 12, $fontsize + 8, $black, $font, $text);
imagettftext($image, $fontsize, 0, 10, $fontsize + 6, $white, $font, $text);

imagettftext($image, 10, 0, 12, $y - 8, $white, $font, "Copyright");
imagettftext($image, 10, 0, 10, $y - 7, $black, $font, "Copyright");

if($ext == "gif") {

	header("Content-type: image/gif");

	imagegif($image);

} else {

	header("Content-type: image/jpeg");

	imagejpeg($image);

}

imagedetroy($image);

?>
