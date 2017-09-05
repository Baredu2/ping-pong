<?php

//POST - opponentId, num, p1_result, p2_result

session_start();
require_once("../db_config.php");

//czy uzytkownik jest zalogowany
if( !(isset($_SESSION['accessLevel']) && ($_SESSION['accessLevel'] == 'user' || $_SESSION['accessLevel'] == 'admin') )) {
	print "problem1";

	return;
}

//czy wartości POST istnieją
if( isset($_POST['opponentId']) == false || isset($_POST['num']) == false || isset($_POST['p1_result']) == false || isset($_POST['p2_result']) == false) {
	// print "opponentId: " . $_POST['opponentId'] . "<br>";
	// print "num: " . $_POST['num'] . "<br>";
	// print "p1_result: " . $_POST['p1_result'] . "<br>";
	// print "p2_result: " . $_POST['p2_result'] . "<br>";
	exit();
}

//czy wartości POST są numerami
if( is_numeric($_POST['opponentId']) == false || is_numeric($_POST['num']) == false || is_numeric($_POST['p1_result']) == false || is_numeric($_POST['p2_result']) == false) {
	print "opponentId: " . is_numeric($_POST['opponentId']) . "<br>";
	print "num: " . is_numeric($_POST['num']) . "<br>";
	print "p1_result: " . $_POST['p1_result'] . "<br>";
	print "p2_result: " . $_POST['p2_result'] . "<br>";
	print "problem 3";

	return;
}

$p1_result = $_POST['p1_result'];
$p2_result = $_POST['p2_result'];

if( $p1_result != $CONFIG['maxPointsInGame'] && $p2_result != $CONFIG['maxPointsInGame'])
{
	print "Mecz powinien trwać do $CONFIG[maxPointsInGame] punktów!";
	return;
}

//ustawianie w kolejności player1 i 2
if($_POST['num'] == 1)
{
	$player1_id = $_SESSION['userID'];
	$player2_id = $_POST['opponentId'];

	$p = "";
}

if($_POST['num'] == 2)
{
	$player1_id = $_POST['opponentId'];
	$player2_id = $_SESSION['userID'];

	$p = "2";
}



$sql = "UPDATE matches SET player1_score$p=$p1_result, player2_score$p=$p2_result, player$_POST[num]_sent=1, end_date=NOW() WHERE player1=$player1_id AND player2=$player2_id AND finished=0";


$conn = $GLOBALS['db_conn'];


if($conn->query($sql) == true) {
	print "success";
}
else {
	print "Error: " . $conn->error;
}

$conn->close();
?>
