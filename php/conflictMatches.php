<?php
session_start();

if($_SESSION['accessLevel'] == 'admin') {


function findConflictMatches()
{
	require_once("../db_config.php");
	$sql_dobry = "SELECT a.id as id_meczu,
		a.player1,
		CONCAT(bp.Name, ' ', bp.surname) AS player1_name,
		bp.number AS numer,
		bp.email AS mail,
        	a.player2,
        CONCAT(bc.Name, ' ', bc.surname) AS player2_name,
		bc.number AS numer,
		bc.email AS mail,
		a.create_date,
		a.player1_score,
		a.player2_score,
		a.player1_score2,
		a.player2_score2,
                a.end_date
        FROM matches AS a
            INNER
				JOIN users AS bp
                ON bp.ID = a.player1
            INNER
				JOIN users AS bc
                ON bc.ID = a.player2
        WHERE finished = 0 AND (player1_score <> player1_score2 OR player2_score <> player2_score2) AND ( (end_date IS NOT NULL AND end_date < DATE(NOW())) OR (player1_sent=1 AND player2_sent=1) )";
	$conn = new mysqli($db_host, $db_user, $db_password, $db_database);
	$conn->query('SET NAMES utf8');
	$conflicts = $conn->query($sql_dobry);

	$conn->close();
	return $conflicts;
}

function isThereConflictMatches()
{
	return findConflictMatches()->num_rows;
}

if(isset($_POST['get']))
{
	print isThereConflictMatches();
}

}

?>
