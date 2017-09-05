<?php 
session_start(); 
?>


<font class="page-title">Ranking</font>


<?php
/*ini_set("display_errors", 0);*/

require_once($_SESSION['rootPath'] . "/db_config.php");
$conn = $GLOBALS['db_conn'];

if( $_SESSION['accessLevel'] == "user" )
	print "<p><a href='#your-place' class='yourplaceinranking'>Twoje miejsce w rankingu  -  <span id='rank'></span></a></p>";

$rankingListSql = "SELECT id, username, name, surname, wins, losses, score FROM users WHERE access NOT LIKE 'admin' ORDER BY score DESC, wins DESC, matches DESC, losses ASC, surname ASC";
$result = $conn->query($rankingListSql);


if($result->num_rows > 0)
{
	echo '<table id="table-userlist">';
		echo '<tr id="tr-userlist-title">';
			echo '<th class="th-username">Miejsce</th>';
			echo '<th class="th-username">Nick</th>';
			echo '<th class="th-name-userlist">Imię</th>';
			echo '<th class="th-surname">Nazwisko</th>';
			echo '<th class="th-wins">Wygranych</th>';
			echo '<th class="th-lossen">Przegranych</th>';
			echo '<th class="th-score">Punktów</th>';
			if($_SESSION['accessLevel'] == 'user'){
				echo '<th class="th-challenge">Wyzwij</th>';
			}
		echo '</tr>';
		
	for($i = 0; $i < $result->num_rows; $i++)
	{   
		$row = $result->fetch_assoc();
		
		
		$placeClass = ["tr-gold", "tr-silver", "tr-brown"];
		$class = ($i < 3 ? $placeClass[$i] : ($i % 2 == 0 ? "tr-userlist-1" : "tr-userlist-2"));
		
		if($_SESSION['user'] == $row['username'])
        {
            $class = "tr-green";
        }
            
		
		ranking($class, $i + 1, $row['id'], $row['username'], $row['name'], $row['surname'], $row['wins'], $row['losses'], $row['score']);
        
	}
	
	echo '</table>';  
}


function ranking($class, $miejsce, $id, $username, $name, $surname, $wins, $losses, $score)
{
	if( $_SESSION['userID'] == $id )
	{
		print "<script>$('#rank').html('$miejsce')</script>";
		$you = "<a id='your-place'></a>";
	}
	
	echo "<tr class='$class'>";
	echo "<th class='th-username'>$miejsce";
        if($miejsce > 0 && $miejsce < 4)
        {
            echo "<i class='icon-award'></i>";
        }
        echo "</th>";
	echo "<th class='th-username'>$you$username</th>";
	echo "<th class='th-name-userlist'>$name</th>";
	echo "<th class='th-surname'>$surname</th>";
	echo "<th class='th-wins'>$wins</th>";
	echo "<th class='th-losses'>$losses</th>";
	echo "<th class='th-score'>$score</th>";
	if($_SESSION['accessLevel'] == 'user'){
		echo "<th class='th-challenge tr-challenge' onclick='challangeplayer($id, \"$name\", \"$surname\")'><i class='icon-paper-plane-empty'></i>Wyzwij</th>";
	}
	echo '</tr>';
}


$conn->close();

?>
