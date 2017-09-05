<?php
session_start();

require_once($_SESSION['rootPath'] . "/db_config.php");
$conn = $GLOBALS['db_conn'];

$_SESSION['lastlogin'] = time();

$conn->query("UPDATE users SET lastlogin='$_SESSION[lastlogin]' WHERE id=$_SESSION[userID]");
$conn->close();
?>
