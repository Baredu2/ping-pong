<?php

session_start();

if( $_SESSION['accessLevel'] != "admin" )
{
	return;
}

if(!isset($_POST['id'])){
	echo $_POST['id'];

	return;
}

require_once($_SESSION['rootPath'] . "/db_config.php");

$id = $_POST['id'];

$conn = $GLOBALS['db_conn'];

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
	exit();
}

$findUserSql = "SELECT * FROM users WHERE id=$id";
$findUser = $conn->query($findUserSql);


if($findUser->num_rows != 1){

	echo "nie ma uzytkownika o takim ID";
}
else {

	$deleteUserSql = "DELETE FROM users WHERE id=$id";
	if( $conn->query($deleteUserSql) == true )
	{
		echo "success";
	}
	else {
		print "Błąd zapytania";
		print $deleteUserSql;
	}

}

$conn->close();
?>
