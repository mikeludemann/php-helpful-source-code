<?php 

defined('SECURE') or die('Direct access to this file is not allowed!'); 

/** 
	* DB Data 
	*/ 

$config['host'] = 'localhost';
$config['username'] = 'root';
$config['password'] = 'admin';
$config['database'] = 'login';


/** 
	* Error Handling
	*/ 

error_reporting(E_ALL); 

ini_set('display_errors', false); 


/** 
	* Connection - Check
	*/ 

$SQL = new MySQLi($config['host'], $config['username'], $config['password'], $config['database']);

if(mysqli_connect_errno() != 0 || !$SQL->set_charset('utf8')) {

	die('<strong>Error:</strong> It was not connect to the database server!'); 

} 

?>
