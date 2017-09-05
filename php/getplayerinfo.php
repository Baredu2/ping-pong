<?php
session_start();


if($_SESSION['accessLevel'] == 'admin' && isset($_POST['id']) && is_numeric($_POST['id'])) {
	
	require_once($_SESSION['rootPath'] . "/db_config.php");
	$conn = $GLOBALS['db_conn'];
	
	
	$selectPlayerSql = "SELECT * FROM users WHERE id=$_POST[id]";
	$result = $conn->query($selectPlayerSql);
	
	if($result->num_rows > 0) {
		
		$playerInfo = $result->fetch_assoc();
		
		print "
		<center><b>$playerInfo[name] $playerInfo[surname]</b></center><br>
		Użytkownik: $playerInfo[username]<br>
		E-Mail: $playerInfo[email]<br>
		Telefon: $playerInfo[number]";
	}
	else {
		
		print "error";
	}

}
else {
	
	print "permissions";
}

$conn->close();
?>