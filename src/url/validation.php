<?php

$url = "https://google.com";

$parsed = parse_url($url);

$fullpath = "$parsed[scheme]://$parsed[host]$parsed[path]";

if ((checkdnsrr($parsed["host"],"A")) == true) {

  $good_dns = true;
  
	if (url_live($parsed["host"], $fullpath)) {

    $good_url = true;
    
  }
  
}

if (!$good_dns || !$good_url){
  
  print '<p align="center"><font face="arial" size="5"><b>Page Not Found.</b></font><br><br>';

	if ($good_dns && !$good_url){

    print '<font face="arial" size="3"><b>Your Domain is correct but the directory/file does not exist.</b></font></p>';
    
	} else{

    print '<font face="arial" size="3"><b>If you feel this is in error, you may need to check your DNS.</b></font></p>';

  }
  
  die();
  
}

function url_live($host, $path){

  $fp = fsockopen ($host, 80);
  
  fputs($fp,"GET $path HTTP/1.0nn");
  
  $line = fgets($fp, 1024);
  
	if (stristr($line, '200 OK')) {

    $end = true;
    
	} else{

    $end = false;
    
  }
  
  fclose($fp);
  
  return $end;
  
}

?>
