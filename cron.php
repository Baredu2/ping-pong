<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Ten php ma wykonywać się cyklicznie za pomocą crona w odstępach np jedna godzina (administrator może ręcznie wywołać ten skrypt z panelu) //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


require_once("db_config.php");
require_once("config.php");

$conn = new mysqli($db_host, $db_user, $db_password, $db_database);
$conn->query('SET NAMES utf8');

$sql = "SELECT * from matches WHERE finished=0 AND player1_sent=1 AND player2_sent=1";


$matches = $conn->query($sql);

if($matches->num_rows > 0)
{
	while($row = $matches->fetch_assoc()) {
		
		$match_id = $row['id'];
		
		//jesli wyniki uczestnikow sie zgadzaja
		if($row['player1_score'] == $row['player1_score2'] && $row['player2_score'] == $row['player2_score2'])
		{
			
			//ustawiamy kto wygral a kto nie
			$winner = $row['player1'];
			$loser = $row['player2'];
			
			//jesli powyzsze zalozenie sie nie zgadza, zamieniamy wygranego z przegranym
			if($row['player1_score'] < $row['player2_score']) {
				list($winner, $loser) = array($loser, $winner);
			}
			
			//mozna dodac dodawanie do logow bledy
			$conn->query("UPDATE matches SET winner=$winner, loser=$loser, finished=1, end_date=NOW() WHERE id=$match_id");
			
			
			/////////////////// DODAWANIE PUNKTOW ZA WYGRANA /////////////////
			
			
			
			//$conn->query("UPDATE users SET score = score + $points WHERE id=$winner");
		}	
	}
	
}

///////////////// Update wpisow userow w bazie na podstawie odbytych meczow, ile maja wygranych, ile przegranych i ogolnie meczow


$sql_wins = "SELECT username, count(winner) as wins FROM users, matches WHERE users.id=matches.winner AND finished=1 GROUP BY username";
$sql_losses = "SELECT username, count(loser) as losses FROM users, matches WHERE users.id=matches.loser AND finished=1 GROUP BY username";

$playerbase = $conn->query("SELECT id FROM users");

if($playerbase->num_rows > 0) {
	
	while($row = $playerbase->fetch_assoc()) {
		
		$id = $row['id'];
		$sql_wins = "SELECT count(winner) as wins FROM users, matches WHERE users.id=matches.winner AND finished=1 AND users.id=$id GROUP BY username";
		
		$wins = $conn->query($sql_wins);
		if($wins->num_rows > 0) {
			$row = $wins->fetch_assoc();
			$numOfWins = $row['wins'];
		}
		else {
			$numOfWins = 0;
		}
	
		
		$sql_losses = "SELECT count(loser) as losses FROM users, matches WHERE users.id=matches.loser AND finished=1 AND users.id=$id GROUP BY username";
		$losses = $conn->query($sql_losses);
		if($losses->num_rows > 0) {
			$row = $losses->fetch_assoc();
			$numOfLosses = $row['losses'];
		}
		else {
			$numOfLosses = 0;
		}
		
		$numOfMatches = $numOfLosses + $numOfWins;
		$playerScore = ($numOfWins * +$CONFIG['pointsPerWin']) + ($numOfMatches * +$CONFIG['pointsPerMatch']);
		
		$conn->query("UPDATE users SET matches=$numOfMatches, wins=$numOfWins, losses=$numOfLosses, score=$playerScore WHERE id=$id");
	}
}

$conn->close();

?>