<?php

session_start();

if( $_SESSION['accessLevel'] != "admin" )
{
	return;
}

if(!isset($_POST['id']) || !isset($_POST['type']) || !is_numeric($_POST['id']) ){

	return;
}

require_once($_SESSION['rootPath'] . "/db_config.php");

$id = $_POST['id'];
$type = $_POST['type'];

$conn = $GLOBALS['db_conn'];

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
	exit();
}

$access = $conn->query("SELECT access FROM users WHERE id=$id")->fetch_assoc()['access'];

if($access == "admin" && $_SESSION['mainAdmin'] == false)
{
  print "Tylko administrator może zablokowac innego administratora";
  return;
}

if($type == "block")
{

  $blockUserSql = "UPDATE users SET blocked=1 WHERE id=$id";
  if( $conn->query($blockUserSql) == true )
  {
    print "success";
  }
  else {
    print "Wystąpił błąd podczas blokowania użytkownika";
  }
}
else if($type == "unblock")
{
  $unblockUserSql = "UPDATE users SET blocked=0 WHERE id=$id";
  if( $conn->query($unblockUserSql) == true )
  {
    print "success";
  }
  else {
      print "Wystąpił błąd podczas odblokowywania użytkownika";
  }
}
else
{
  print "Nieznana akcja.";
}
$conn->close();
?>
