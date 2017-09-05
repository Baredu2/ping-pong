<?php
session_start();

if( $_SESSION['accessLevel'] != "admin" || $_SESSION['accessLevel'] != "user" )
{
	print "Brak uprawnień";
	return;
}

if ( !isset($_POST['password']) || !isset($_POST['newpassword']) || !isset($_SESSION['user']) )
{
	/*if (!isset($_POST['submit']))
		echo 'submit';
	if (!isset($_SESSION['user']))
		echo 'username';
	if (!isset($_POST['password']))
		echo 'password';
	if (!isset($_POST['newpassword']))
		echo 'newpassword';*/
	
	print "Błąd danych wejściowych";
	return;
}
else
{
	require_once($_SESSION['rootPath'] . '/db_config.php');
	
	$conn = new mysqli($db_host, $db_user, $db_password, $db_database);
	$conn->query('SET NAMES utf8');
	
	$password = htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");
	$newpassword = htmlspecialchars($_POST['newpassword'], ENT_QUOTES, "UTF-8");
	$newpassword = password_hash($newpassword, PASSWORD_BCRYPT);
	
	$username = $_SESSION['user'];

	$result = $conn->query(sprintf("SELECT username, password FROM users WHERE username='%s'", $username));
	$count = $result->num_rows;

	if ($count == 1)
	{
		$details = $result->fetch_assoc();
		$user_pass = $details['password'];

		if (password_verify($password, $user_pass))
		{
			$res = $conn->query("UPDATE users SET password='$newpassword' WHERE username LIKE '$username'");
			//print $newpassword;
			//print "UPDATE users SET password=$newpassword WHERE username LIKE '$username'";
			print "success";
		}
		else {
			print "Błędne hasło";
		}
		//print "l";

	}
	else
		print "Błąd z wybraniem użytkownika";

	
	$conn->close();
}
?>