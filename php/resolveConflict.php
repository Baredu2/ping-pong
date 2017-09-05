<?php
session_start();

if($_SESSION['accessLevel'] != 'admin') {
	
	print "permissions";
	return;
}

require_once("../db_config.php");
require_once("../config.php");



if( !isset($_POST['matchId']) || !isset($_POST['p1_score']) || !isset($_POST['p2_score']) || !isset($_POST['p1_score2']) || !isset($_POST['p2_score2']) ) {
	
	print "Błąd danych wejściowych";
	return;
}

if( !is_numeric($_POST['matchId']) || !is_numeric($_POST['p1_score']) || !is_numeric($_POST['p2_score']) || !is_numeric($_POST['p1_score2']) || !is_numeric($_POST['p2_score2']) ) {
	
	print "Dane wejściowe nie są liczbami";
	return;
}

if( $_POST['p1_score'] != $CONFIG['maxPointsInGame'] && $_POST['p2_score'] != $CONFIG['maxPointsInGame'])
{
	print "Zwycięzca powinien mieć $CONFIG[maxPointsInGame] punktów!";
	return;
}

$conn = new mysqli($db_host, $db_user, $db_password, $db_database);
$conn->query('SET NAMES utf8');


$doesMatchExistSql = "SELECT * FROM matches WHERE id=$_POST[matchId] AND finished=0 AND end_date IS NOT NULL";
$doesMatchExist = $conn->query($doesMatchExistSql);

if($doesMatchExist->num_rows == 1)
{
	$updateMatchScoreSql = "UPDATE matches SET player1_score=$_POST[p1_score], player2_score=$_POST[p2_score], player1_score2=$_POST[p1_score2], player2_score2=$_POST[p2_score2], player1_sent=1, player2_sent=1, end_date=NOW() WHERE id=$_POST[matchId] AND finished=0";
	if($conn->query($updateMatchScoreSql) == true) 
	{
		
		print "success";
	}
	else 
	{
		print "Nie udało się zaktualizować meczu";
		print "$updateMatchScoreSql";
	}
}
else 
{
	print "Nie znaleziono meczu";
}

$conn->close();
?>