<?php
session_start();

if($_SESSION['accessLevel'] != "admin")
{
	header('Location: ../index.php');
	exit();
}

if (!isset($_POST['newpassword']) || !isset($_SESSION['user']) || !isset($_POST['id']) || !is_numeric($_POST['id']) || $_POST['id'] == 1)
{
	print "checkerror";
	exit();
}

require_once($_SESSION['rootPath'] . "/db_config.php");
$conn = $GLOBALS['db_conn'];


$id = $_POST['id'];
$newpassword = htmlspecialchars($_POST['newpassword'], ENT_QUOTES, "UTF-8");
$newpassword = password_hash($newpassword, PASSWORD_BCRYPT);


$findUserSql = sprintf("SELECT * FROM users WHERE id=%d", $id);
$findUser = $conn->query($findUserSql);


if ($findUser->num_rows == 1)
{
	
	$conn->query("UPDATE users SET password='$newpassword' WHERE id=" . $id);

	print "success";
}
else
	print "notfound";


$conn->close();
?>