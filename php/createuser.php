<?php
session_start();

if (!isset($_POST['username']) || 
		!isset($_POST['name']) || 
		!isset($_POST['surname']) ||
		!isset($_POST['password']) ||
		!isset($_POST['email']) ||
		!isset($_POST['number']) ||
		!isset($_POST['access']) )
{
	if (!isset($_POST['username']))
		echo 'no username<br>';
	if (!isset($_POST['name']))
		echo 'no name<br>';
	if (!isset($_POST['surname']))
		echo 'no surname<br>';
	if (!isset($_POST['password']))
		echo 'no password<br>';
	if (!isset($_POST['email']))
		echo 'no email<br>';
	if (!isset($_POST['number']))
		echo 'no number<br>';
	if (!isset($_POST['access']))
		echo 'no access<br>';
	
	
	//header('Location: ../index.php');
	exit();
}

if($_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == "administrator") {
	$isMainAdmin = true;
}
else {
	$isMainAdmin = false;
}

// Create connection
require_once($_SESSION['rootPath'] . "/db_config.php");

$conn = $GLOBALS['db_conn'];

$username = htmlspecialchars($_POST['username'], ENT_QUOTES, "UTF-8");
$name = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
$surname = htmlspecialchars($_POST['surname'], ENT_QUOTES, "UTF-8");
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
$number = htmlspecialchars($_POST['number'], ENT_QUOTES, "UTF-8");
$access = htmlspecialchars($_POST['access'], ENT_QUOTES, "UTF-8");

if($access == "admin" && $isMainAdmin == false) {
	
	print "Tylko głowny administrator może tworzyć adminów";
	return;
}


// Zhashuj hasło użytkownika
$password = password_hash($password, PASSWORD_BCRYPT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$createUserSql = "SELECT count(*) as count FROM users WHERE username='$username'";
$result = $conn->query($createUserSql);
$row = $result->fetch_assoc();

if($row['count'] > 0) {
	
	echo "Taki uzytkownik juz istnieje";
}
else {	

	$sql = "INSERT INTO users (username, password, email, name, surname, number, access)
	VALUES ('$username', '$password', '$email', '$name', '$surname', '$number', '$access')";

	if ($conn->query($sql) == TRUE) {
		
		echo "success";
	} 
	else {
		
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

$conn->close();
?>