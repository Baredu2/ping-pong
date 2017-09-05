<?php

session_start();

if( $_SESSION['accessLevel'] != "admin" )
{
	return;
}

if( !isset($_POST['matchId']) )
{
	
	print "Brak podanego ID meczu";
	return;
}

if( !is_numeric($_POST['matchId']) )
{
	print "ID nie jest liczbą!";
	return;
}
////////////////

require_once("../db_config.php");
$conn = $GLOBALS['db_conn'];


$id = $_POST['matchId'];

if ($conn->connect_error) {
	
    die("Connection failed: " . $conn->connect_error);
	exit();
} 

$findMatchSql = "SELECT id FROM matches WHERE id=$id AND finished=0";
$findMatch = $conn->query($findMatchSql);


if($findMatch->num_rows != 1){
	
	echo "Nie ma takiego aktywnego meczu";
}
else 
{
	
	$deleteUserSql = "DELETE FROM matches WHERE id=$id AND finished=0";
	$conn->query($deleteUserSql);
	
	echo "success";
}

$conn->close();
?>