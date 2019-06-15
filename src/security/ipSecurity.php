<?php 

function ipFillZeros($ip) { 
   
	if (substr($ip, 0, 2) == '::') {
    
    $ip = 0 . $ip; 
  
  }

	if (strpos($ip, '::')) { 
    
		if (substr($ip, -2, 2) == '::') {
      
      $ip .= '0';
    
    } 
 
		$ipBlocks = explode(':', $ip); 
		$count = 8 - count($ipBlocks); 
		$ip = ''; 

		foreach ($ipBlocks AS $ipBlock) { 

			if ($ip != '') {
        
        $ip .= ':'; 
      
      }

			if ($ipBlock == '') { 

				for ($i = 0; $i <= $count; $i++) { 

					if ($ip != '' && $i != 0) {
            
            $ip .= ':'; 
          
          }

          $ip .= '0'; 
          
        } 
        
			} else { 

        $ip .= $ipBlock; 
        
      } 
      
    } 
    
	} 

  return $ip; 
  
} 

function isIPv6($ip) { 

  return (preg_match('#^[0-9A-F]{0,4}(:([0-9A-F]{0,4})){0,7}$#s', $ip)) ? true : false; 
  
} 

function isIPv4($ip) {

  return (preg_match('#^[0-9]{1,3}(\.[0-9]{1,3}){3}$#', $ip)) ? true : false; 
  
} 

function checkIP($ip, $compareIP, $blocks4 = 2, $blocks6 = 4) { 

	$result = true; 

	if (isIPv6($ip) && isIPv6($compareIP)) { 

    if ($blocks6 > 8){
      
      $blocks6 = 8;
    
    }
    
		$ip = ipFillZeros($ip); 
		$compareIP = ipFillZeros($compareIP); 

		$ipBlocks = explode(':', $ip); 
    $compareIPBlocks = explode(':', $compareIP); 
    
		for ($i = 0; $i < $blocks6; $i++) { 

			if ($ipBlocks[$i] != $compareIPBlocks[$i]) { 

        $result = false; 
        
        break; 
        
      } 
      
    } 
    
	} elseif (isIPv4($ip) && isIPv4($compareIP)) { 

    if ($blocks4 > 4){
      
      $blocks4 = 4;
    
    }
    
		$ipBlocks = explode('.', $ip); 
    $compareIPBlocks = explode('.', $compareIP); 
    
		for ($i = 0; $i < $blocks4; $i++) { 

			if ($ipBlocks[$i] != $compareIPBlocks[$i]) { 

        $result = false; 
        
        break; 
        
      } 
      
    } 
    
	} else { 

    $result = false; 
    
	} 

  return $result; 
  
} 

?>
