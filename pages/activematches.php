<?php	
session_start();

if($_SESSION['accessLevel'] == 'user' ||
          $_SESSION['accessLevel'] == 'admin' ) { ?>

<script async src="../js/core.js"></script>
<font class="page-title">Zarządzaj aktywnymi meczami...</font><br>

    
<?php

if($_SESSION['accessLevel'] != "admin")
{
	return;
}

require_once($_SESSION['rootPath'] . "/db_config.php");
require_once($_SESSION['rootPath'] . "/php/conflictMatches.php");

$conn = $GLOBALS['db_conn'];

$findAllActiveMatchesSql = "SELECT a.id as id_meczu,
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
        WHERE finished = 0";
$findAllActiveMatches = $conn->query($findAllActiveMatchesSql);

if($findAllActiveMatches->num_rows > 0) {
	
	echo '<table id="table-userlist">';
		echo "<tr id='tr-userlist-title' style='width: 100%;'>
			<th class='th-username' style='width: 4%;'>ID meczu</th>
			<th class='th-username' style='width: 13%;'>Data utworzenia</th>
			<th class='th-username' style='width: 26%;'>Gracz 1</th>
			<th class='th-username' style='width: 26%;'>Gracz 2</th>
			<th class='th-username' style='width: 25%;'>Wyniki</th>
			<th class='th-username' style='width: 6%;'></th>
		</tr>";
	for($i = 0; $i < $findAllActiveMatches->num_rows; $i++)
	{   
		$row = $findAllActiveMatches->fetch_assoc();
		$id_match = $row['id_meczu'];
		$createdate = $row['create_date'];
		
		$player1['id'] = $row['player1'];
		$player1['name'] = $row['player1_name'];
		$player1['p1_score'] = $row["player1_score"];
		$player1['p2_score'] = $row["player2_score"];    
		$player1['score'] = ($player1['p1_score'] == -1 || $player1['p2_score'] == -1 ? "Brak" : "$player1[p1_score]<font style='font-family: Arial'>:</font>$player1[p2_score]");  
		
		$player2['id'] = $row['player2'];
		$player2['name'] = $row['player2_name'];
		$player2['p1_score'] = $row["player1_score2"];
		$player2['p2_score'] = $row["player2_score2"];
		$player2['score'] = ($player2['p1_score'] == -1 || $player2['p2_score'] == -1 ? "Brak" : "$player2[p1_score]<font style='font-family: Arial'>:</font>$player2[p2_score]");
		
		$dzien = date("j", strtotime($createdate));
		$miesiac = date("n", strtotime($createdate));
		$rok = date("Y", strtotime($createdate));
		$godzina = date("H", strtotime($createdate));
		$minuta = date("i", strtotime($createdate));

		$miesiac_pl = array(1 => 'Stycznia', 'Lutego', 'Marca', 'Kwietnia', 'Maja', 'Czerwca', 'Lipca', 'Sierpnia', 'Września', 'Października', 'Listopada', 'Grudznia');    
		
		
		$class = ( $i % 2 == 0 ? "tr-userlist-1" : "tr-userlist-2");
		
		
		echo "<tr class='$class'>
		<th class='th-username'>$id_match</th>
		<th class='th-username'>$dzien $miesiac_pl[$miesiac] $rok, $godzina<font style='font-family: Arial'>:</font>$minuta</th>
		<th class='th-name-userlist'><font onclick='displayPlayerInfo($player1[id])'>$player1[name]</font> - $player1[score]</th>
		<th class='th-name-userlist'><font onclick='displayPlayerInfo($player2[id])'>$player2[name]</font> - $player2[score]</th>
		<th class='th-username'>
			<input type='text' placeholder='$player1[name]' class='input-approval' type='number' minlength='1' maxlength='2' name='wynik1-$i' id='wynik1-$i' style='margin-right: 2px' />
			<input type='text' placeholder='$player2[name]' class='input-approval' type='number' minlength='1' maxlength='2' name='wynik2-$i' id='wynik2-$i' />
		</th>
		<th class='th-name-userlist'>
			<font onclick='updateMatch($i, $id_match)' class='icon-ok'>  </font>
		<font onclick='deleteMatch($id_match)' class='icon-cancel delete'></font></th>
		</tr>";
	}
	
	echo '</table><br><br>';
}
?>

<script>

function deleteMatch(matchId) 
{
	
	alertify.confirm("Czy na pewno chcesz usunąć ten mecz o ID " + matchId + "?", function(e) {
		if(e) {
			
			$.post("php/deleteMatch.php", { "matchId": matchId }, function(result) {
			
				if(result == "success")
				{
					alertify.success("Mecz został usunięty");
				}
				else 
				{
					alertify.error(result);
					console.log(result);				
					
				}
				
				$('#content').load('pages/activematches.php');
			});
		}
	});
	
}

function updateMatch(i, matchId) 
{
	
	var p1_score = $('#wynik1-' + i).val();
	var p2_score = $('#wynik2-' + i).val();
	
	var p1_score2 = p1_score;
	var p2_score2 = p2_score;
	
	if(isNaN(p1_score) || isNaN(p2_score) || p1_score === '' || p2_score === '') {
		
		alertify.error("Wyniki muszą być liczbami");
		return;
	}

	if(p1_score < 0 || p2_score < 0) {
		
		alertify.error("Wyniki muszą być dodatnie");
		return;
	}
	
	alertify.confirm("Czy na pewno chcesz ustawić wynik meczu o id " + matchId + " <br><b>wynikiem " + p1_score + " : " + p2_score + "</b>", function(e) {
		if(e) {
			
			$.post("php/setMatch.php", {"matchId": matchId, "p1_score": p1_score, "p2_score": p2_score, "p1_score2": p1_score2, "p2_score2": p2_score2}, function(result) {
			
				if(result == "success")
				{
					alertify.success("Mecz został zaktualizowany");
				}
				else 
				{
					alertify.error(result);
					console.log(result);				
					
				}
				
				$('#content').load('pages/activematches.php');
			});
		}
	});
	
}

</script>

<?php } ?>