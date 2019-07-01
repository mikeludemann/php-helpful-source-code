<?php

function ServerInfo($ServerURL){

	$server = fsockopen($ServerURL,80,$errno,$errstr);

	if(!$server) {

		$WebServer= "Error: $errstr ($errno)<br>";

	} else {

		fputs($server, "GET / HTTP/1.0nn");

		while(!feof($server)) {

			$WebServer=fgets($server,4096);

			if (ereg( "^Server:",$WebServer)) {

				$WebServer=trim(ereg_replace( "^Server:", "",$WebServer));

				break;

			}

		}

		fclose($filepointer);

	}

	return($WebServer);

}

if ($ServerURL<> "") { $WebServer=ServerInfo($ServerURL); }

?>

<html>
<head>
	<title>Server Informations</title>
</head>
<body style="background-color: #c0c0c0;">

<?php

back();
if ($WebServer <> "" and $ServerURL <> "") { 

	echo( "<font color=darkblue size=4><b><PRE>Server $ServerURL is running $WebServer.</PRE></font>"); 

	} 

?>

<br><br>
<form action=" <?php echo($PHP_SELF); ?>" method="post">
	<font color="darkblue"><b>http://</b></font>
	<input type="text" name="ServerURL" size="40" maxlength="100">
	<input type="hidden" name="WebServer" value="">
	<input type="submit" value="Get this Server informations!">
	<input type="reset" value="Reset">
</form>
</body>
</html>
