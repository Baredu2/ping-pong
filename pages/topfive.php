<?php
session_start();

/*Jak było dołączone z osobnego pliku to wywala błąd;*/
require_once($_SESSION['rootPath'] . "/db_config.php");


$conn = $GLOBALS['db_conn'];

$topFiveSql = "SELECT name, surname FROM users WHERE access NOT LIKE 'admin' ORDER BY score DESC, wins DESC, matches DESC, losses ASC, surname ASC LIMIT 5";
$topFive = $conn->query($topFiveSql);


for($i = 0; $i < $topFive->num_rows; $i++)
{   
	$row = $topFive->fetch_assoc();
	
	topRow($i + 1, $row['name'], $row['surname']);
}


function topRow($miejsce, $name, $surname)
{
	echo '<tr>';
	echo "<th class='th-number'>$miejsce</th>";
	echo "<th class='th-name'>$name $surname</th>";
	echo '</tr>';
}

$conn->close();
?>