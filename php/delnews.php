<?php 
session_start();


if($_SESSION['accessLevel'] == 'admin' && isset($_POST['id']) && is_numeric($_POST['id'])) {
	
	require_once($_SESSION['rootPath'] . "/db_config.php");
 
	$conn = $GLOBALS['db_conn'];

	$deleteNewsSql = sprintf("DELETE FROM News WHERE id=%d", $_POST['id']);
	$result = $conn->query($deleteNewsSql);
	
	$conn->close();
}
?>