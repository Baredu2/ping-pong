<?php
session_start();


if( $_SESSION['accessLevel'] != 'admin' || $_SESSION['mainAdmin'] != true || $_SESSION['userID'] != 1 )
{
	print "Brak uprawnień";
	return;
}
else
{
	require_once("../db_config.php");
	$conn = $GLOBALS['db_conn'];
	
	
	foreach($_POST as $name => $value)
	{
		$key = htmlspecialchars($key);
		$value = htmlspecialchars($value);
		
		$updateSettingsSql = "UPDATE settings SET value='$value' WHERE name='$name'";
		if ($conn->query($updateSettingsSql) == true) 
		{			
			
		}
		else 
		{			
			print "Błąd zapytania przy $name";
			return;
		}
	}
	
	print "success";
}










?>