
<?php 

function thumbnail($imageFile, $filepath="./images/thumbnail/", $filenameOnly=true){ 
  
	$thumbsize = 256; 

	$filename = basename($imageFile); 

	if(!$filenameOnly){ 

		$replace = array("/","\\","."); 
    $filename = str_replace($replace,"_",dirname($imageFile))."_".$filename; 
    
  } 

	$path = $filepath; 

  if(!is_dir($path)) {
    
    return false;

  }

	if(file_exists($path.$filename)) {
    
    return $path.$filename;
    
  } 
	
	if(!file_exists($imageFile)) {
    
    return false;

  }

	$endFile = strrchr($imageFile,"."); 

  list($width, $height) = getimagesize($imageFile); 
  
	$imageRatio=$width/$height; 

	if($imageRatio>1) { 

		$newWidth = $thumbsize; 
    $newHeight = $thumbsize/$imageRatio; 
    
  } else { 

		$newHeight = $thumbsize; 
    $newWidth = $thumbsize*$imageRatio; 
    
  } 

	if(function_exists("imagecreatetruecolor")) {

    $thumb = imagecreatetruecolor($newWidth,$newHeight); 
  
  }	else {

    $thumb = imagecreate ($newWidth,$newHeight);
  
  }

	if($endFile == ".jpg"){ 

		imageJPEG($thumb,$path."temp.jpg"); 
		$thumb = imagecreatefromjpeg($path."temp.jpg"); 

    $source = imagecreatefromjpeg($imageFile); 
    
	} else if($endFile == ".gif") { 

		imageGIF($thumb,$path."temp.gif"); 
    $thumb = imagecreatefromgif($path."temp.gif"); 
    
    $source = imagecreatefromgif($imageFile); 
    
  } 

	imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

	if($endFile == ".png") {

    imagepng($thumb,$path.$filename);
  
  }	else if($endFile == ".gif") {
    
    imagegif($thumb,$path.$filename);
  
  }	else {

    imagejpeg($thumb,$path.$filename,100);
  
  }

	ImageDestroy($thumb); 
	ImageDestroy($source); 

  return $path.$filename; 
  
} 

?>
