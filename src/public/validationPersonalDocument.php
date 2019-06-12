<?php 

function checkPersonalNumber($number = 0){ 

	$numberPersonal = 7; 

	for($i = 0; $i < strlen(strval($number)); $i++) { 

		$validContent += substr($number[$i]*$numberPersonal, -1); 

		if($numberPersonal == 1) {

			$numberPersonal = 7; 

		} else if($numberPersonal == 3) {

			$numberPersonal = 1; 

		} else if($numberPersonal == 7) {

			$numberPersonal = 3; 

		}

	}

	return $validContent; 

} 

function checkPersonalDocument($id) { 

	$array = explode(" ", $id); 

	if(! (substr(checkPersonalNumber(substr($array[0], 0, 9)), -1) == substr($array[0], 9, 1))) {

		return false; 

	}

	if(! (substr(checkPersonalNumber(substr($array[1], 0, 6)), -1) == substr($array[1], 6, 1)))  {

		return false; 
		
	}

	if(! (substr(checkPersonalNumber(substr($array[2], 0, 6)), -1) == substr($array[2], 6, 1)))  {

		return false; 
		
	}

	if(! (time() < mktime(0,0,0, substr($array[2], 2, 2) , substr($array[2], 4, 2) , substr($array[2], 0, 2))))  {

		return false; 
		
	}

	if(! (substr(checkPersonalNumber(substr($array[0], 0, 10).substr($array[1], 0, 7).substr($array[2], 0, 7)), -1) == $array[3])) {

		return false; 
		
	}

	return true; 
	
} 

function checkPersonalInformation($id) { 

	$array = explode(" ", $id); 

	$validContent->geb->tag= $array[1]{4} . $array[1]{5};
	$validContent->geb->monat = $array[1]{2} . $array[1]{3};
	$validContent->geb->jahr = "19" . $array[1]{0} . $array[1]{1};

	$alter = date("Y") - $validContent->geb->jahr; 
 
	if( (date("n") < $validContent->geb->monat) OR (date("n") == $validContent->geb->monat AND date("j") < $validContent->geb->tag) ) {

		$alter--; 

	}

	$validContent->alter = $alter; 

	if($alter >= 18)  {

		$validContent->volljaehrig = true; 
		
	} else  {

		$validContent->volljaehrig = false; 
		
	}


	$validContent->ablauf->tag = $array[2]{4} . $array[2]{5};
	$validContent->ablauf->monat = $array[2]{2} . $array[2]{3};
	$validContent->ablauf->jahr = $array[2]{0} . $array[2]{1};

	$validContent->herkunft = $array[0]{10}; 

	if(strtolower($validContent->herkunft) == "d") {

		$validContent->deutscher = true; 

	}	else  {

		$validContent->deutscher = false;
		
	} 
 
	$validContent->erstwohnsitz = $array[0]{0}.$array[0]{1}.$array[0]{2}.$array[0]{3}; 

	return $validContent; 

} 

if($_GET['check']) { 

	$personalID = $_POST['ida']." ".$_POST['idb']." ".$_POST['idc']." ".$_POST['idd']; 

	if(checkPersonalDocument($personalID)) { 

		echo "Personalnummer korrekt!"; 

		echo "<br><br> Daten der Ausweisnummer: <pre>"; 

		$data = checkPersonalInformation($personalID); 

		print_r($data); 

		echo "</pre>"; 
	
	} else { 

		echo "Personalnummer falsch"; 

	} 

} 

?> 

<div>
	Personalnummer:
</div> 
<form action="?check=1" method="post" > 
	<div>
		<input type="text" size="11" maxlength="11" name="ida"> 
	</div>
	<div>
		<input type="text" size="7" maxlength="7" name="idb"> 
	</div>
	<div>
		<input type="text" size="7" maxlength="7" name="idc">  
	</div>
	<div>
		<input type="text" size="1" maxlength="1" name="idd">  
	</div>
	<div>
		<input type="submit" value="überprüfen">  
	</div>
</form>
