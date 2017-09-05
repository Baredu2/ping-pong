<?php
session_start();


if($_SESSION['accessLevel'] != 'admin') {
	

	return;
}

if (!isset($_POST['title']) || 
		!isset($_POST['content']) ||
		!isset($_POST['id']) ||
		!is_numeric($_POST['id'])) {
	
	print "inputerror";
	return;
}

require_once($_SESSION['rootPath'] . "/db_config.php");


// Lączenie z bazą danych
$conn = $GLOBALS['db_conn'];


$title = htmlspecialchars($_POST['title'], ENT_QUOTES, "UTF-8");
$content = htmlspecialchars($_POST['content'], ENT_QUOTES, "UTF-8");


// Sprawdzanie połączenia
if ($conn->connect_error) {
	
    print "databaseerror";
	return;
} 


// Dodanie posta na strone 
$updatePostSql = "UPDATE News SET title='$title', news='$content', edit_author='$_SESSION[user]' WHERE id=$_POST[id]";
if ($conn->query($updatePostSql) == true) {
    
	print "success";
}
else {
	
    print "queryerror";
	return;
}

$conn->close();
?>