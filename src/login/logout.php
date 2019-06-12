<?php 

session_start(); 

if(!isset($_SESSION['id'])){ 

	die('You are not login! <a href="login.php">Login</a>'); 

} 

?> 

<!DOCTYPE HTML> 
<html> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
		<title>Logout</title> 
	</head> 
	<body> 
		<div>
			<span>Welcome.</span>
			<a href="login.php?logout">Logout</a>
		</div> 
	</body> 
</html>
