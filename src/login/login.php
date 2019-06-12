<?php 

session_start(); 

define('SECURE', true); 

require_once('config.php'); 

if(isset($_GET['logout'])){ 

	if(isset($_SESSION['id'])){ 

		$_SESSION = array(); 

		session_destroy(); 

	} 

  header('location: login.php'); 

  exit(); 

} 

if(isset($_POST['send'])){ 

	$email = trim(htmlspecialchars($_POST['email'])); 
	$password = trim(htmlspecialchars($_POST['password'])); 

	if(filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)){ 

		$query = $SQL->prepare('SELECT `id` FROM `user` WHERE `email` = ? AND `password` = ?'); 
		$query->bind_param('ss', $_POST['email'], md5($_POST['password'])); 
		$query->execute(); 
		$query->store_result(); 
		$query->bind_result($id); 

		if($query->num_rows == 1){ 

			$query->fetch(); 

			$_SESSION['id'] = $id; 

			header('location: logout.php'); 

			exit(); 

		} else { 

			$error = 'Your credentials are incorrect. Please repeat your entry.'; 

    } 

	} else { 

		$error = 'Please fill out all fields correctly.'; 

  } 

} else { 

	$error = NULL; 
  $email = NULL; 

} 

?> 

<!DOCTYPE HTML> 
<html> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
		<title>Login</title> 
	</head> 
	<body> 
		<?php echo $error; ?> 
		<form action="login.php" method="post"> 
			<table cellpadding="1" cellspacing="4"> 
				<tr> 
					<td>
						<strong>E-Mail:</strong>
					</td> 
					<td>
						<input type="email" name="email" value="<?php echo $email; ?>" required="required" placeholder="E-Mail" maxlength="255" />
					</td> 
				</tr> 
				<tr> 
					<td>
						<strong>Password:</strong>
					</td> 
					<td>
						<input type="password" name="password" required="required" placeholder="Password" maxlength="50" />
					</td> 
				</tr> 
				<tr> 
					<td colspan="2">
						<input type="submit" name="send" value="Login" />
					</td> 
				</tr> 
			</table> 
		</form> 
	</body> 
</html>
