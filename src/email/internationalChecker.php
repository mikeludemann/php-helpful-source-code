<?php

function checkmail($email) {
  
  if (preg_match("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*.([a-z]{2}|com|edu|gov|int|mil|net|org|shop|aero|biz|coop|info|museum|name|pro)$", $email, $check)) { 
    
    if(getmxrr(substr(strstr($check[0], '@'), 1), $validate_email_temp)) { 

      return true;

    }

    if(checkdnsrr(substr(strstr($check[0], '@'), 1),"ANY")){

      return true;

    }

  }

  return false;


}
 
 
$check = checkmail("info@web.de");
 
if (!$check) echo "Error"; else echo "ok";

?>