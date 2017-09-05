<?php
session_start();


if($_SESSION['accessLevel'] != 'admin' || $_SESSION['mainAdmin'] == false) {
	

	return;
}

if (!isset($_POST['page']) || 
		!isset($_POST['html'])) 
{
	
	print "Brak danych wejsciowych";
	return;
}

require_once($_SESSION['rootPath'] . "/db_config.php");


// Lączenie z bazą danych
$conn = $GLOBALS['db_conn'];

$page = htmlspecialchars($_POST['page'], ENT_QUOTES, "UTF-8");
$content = htmlentities($_POST['html']);


// Sprawdzanie połączenia
if ($conn->connect_error) {
	
    print "Błąd połączenia z bazą";
	return;
} 


// Dodanie posta na strone 
$updatePostSql = "UPDATE pages SET html='$content' WHERE page='$page'";
if ($conn->query($updatePostSql) == true) {
    
	print "success";
}
else {
	
    print "Błąd zapytania SQL";
	return;
}

$conn->close();
?>