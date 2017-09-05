<?php
session_start();

if($_SESSION['accessLevel'] != 'admin') {
	
	return;
}

if(!isset($_POST['id']) || !isset($_POST['type']) || !isset($_POST['value']) || !is_numeric($_POST['id'])) {
	
	print "inputerror";
	return;
}

if($_POST['id'] == 1) {
	
	if( !($_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == "administrator") ) {
		
		print "Nie można zmienić danych głownego administratora";
		return;
	}
}

$value = htmlspecialchars($_POST['value'], ENT_QUOTES, "UTF-8");

if($_POST['type'] == "telefon")
	$field = "number";

else if($_POST['type'] == "email")
	$field = "email";

else {
	
	print "unknowntype";
	return;
}

require_once($_SESSION['rootPath'] . "/db_config.php");
$conn = $GLOBALS['db_conn'];

$changePlayerInfoSql = "UPDATE users SET $field='$value' WHERE id=$_POST[id]";
//print $changePlayerInfoSql;
$conn->query($changePlayerInfoSql);


print "success";

$conn->close();
?>