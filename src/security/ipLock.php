<?php 

function checkingIP($ip) { 

	if(in_array($_SERVER['REMOTE_ADD'], $ip)) { 

    return true; 
    
	} else { 

    return false; 
    
  } 
  
} 

// Invalid IP Address

$ip = array("255.168.12.260", "182.11.0.270", "132.255.210.280", "182.97.142.290", "12.65.10.299");

if(checkingIP($ip)) { 

  header("Location: index.php"); 
  
  exit; 
  
} 

?>
