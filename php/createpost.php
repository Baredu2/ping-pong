<?php
session_start();


if($_SESSION['accessLevel'] != 'admin') {
	
	header("Location: ../index.php");
	return;
}

if (!isset($_POST['title']) || 
		!isset($_POST['message']))
{
	if (!isset($_POST['title']))
		//echo 'title<br>';
	if (!isset($_POST['message']))
		//echo 'message<br>';
	
	//$_SESSION['username']
	//header('Location: ../index.php');
	return;
}

require_once($_SESSION['rootPath'] . "/db_config.php");

// Lączenie z bazą danych
$conn = $GLOBALS['db_conn'];

$title = htmlspecialchars($_POST['title'], ENT_QUOTES, "UTF-8");
$message = htmlspecialchars($_POST['message'], ENT_QUOTES, "UTF-8");
$author = $_SESSION['user'];


// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


// Dodanie posta na strone 
$sql = "INSERT INTO News (title, news, author, date) VALUES ('$title', '$message', '$author', NOW())";


if ($conn->query($sql) == true) {
	$conn->close();
    header("Location: ../index.php");
}
else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>