<?php    
session_start();

require_once($_SESSION['rootPath'] . "/db_config.php");
$conn = new mysqli($db_host, $db_user, $db_password, $db_database);
$conn->query('SET NAMES utf8');

$lastResultsSql = "SELECT a.player1 
		 , bp.Name AS player1_name
		 , a.player2
		 , bc.Name AS player2_name
		 , a.player1_score
		 , a.player2_score
		  FROM matches AS a
		INNER
		  JOIN users AS bp
			ON bp.ID = a.player1
		INNER
		  JOIN users AS bc
			ON bc.ID = a.player2
		WHERE finished = 1
		ORDER BY end_date DESC, create_date DESC
		LIMIT 5";
$lastResults = $conn->query($lastResultsSql);


for($i = 0; $i < $lastResults->num_rows; $i++)
{   
	$row = $lastResults->fetch_assoc();
	
	latestResultsRow($row['player1_name'], $row['player2_name'], $row['player1_score'], $row['player2_score']);
}    

function latestResultsRow($player1, $player2, $player1_score, $player2_score)
{
	echo '<tr>';
	echo "<th class='th-player'>$player1</th>";
	echo "<th class='th-score'>$player1_score</th>";
	echo "<th class='th-colon'><font style='font-family: Arial'>:</font></th>";
	echo "<th class='th-score'>$player2_score</th>";
	echo "<th class='th-player'>$player2</th>";
	echo '</tr>';
}

$conn->close();
?>