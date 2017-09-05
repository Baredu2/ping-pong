<?php
session_start();

if($_SESSION['accessLevel'] == 'admin') {

	print "<font class='page-title'>Konta z uprawnieniami administracyjnymi nie mogą rozgrywać meczy</font><br><br>";
	return;
}

if($_SESSION['accessLevel'] != 'user')
{
	return;
}



?>


<font class="page-title">Moje wyzwania</font><br><br>

<?php
    require_once($_SESSION['rootPath'] . "/db_config.php");
	$userId = $_SESSION['userID'];

    $conn = new mysqli($db_host, $db_user, $db_password, $db_database);
    $conn->query('SET NAMES utf8');


    $userId = $_SESSION['userID'];

    $stmt = $conn->prepare("SELECT DISTINCT * FROM challanges, users WHERE opponent_id=users.id AND host_id=? AND accepted=0");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $my_challanges = $stmt->get_result();

    if($my_challanges->num_rows > 0) {

        echo '<table id="table-userlist">';
            echo '<tr id="tr-userlist-title">';
                echo '<th class="th-username">Przeciwnik</th>';
                echo '<th class="th-username">Imie Nazwisko</th>';
                echo '<th class="th-username">Data utworzenia</th>';
                echo '<th class="th-name-userlist">Status</th>';
                echo '<th class="th-name-userlist"></th>';
            echo '</tr>';
        for($i = 0; $i < $my_challanges->num_rows; $i++)
        {
            $row = $my_challanges->fetch_assoc();
            $opponent = $row['username'];
			$opponent_id = $row['opponent_id'];
			$host_id = $_SESSION['userID'];
            $createdate = $row['created_date'];

			$name = $row['name'];
			$surname = $row['surname'];

            $dzien = date("j", strtotime($createdate));
            $miesiac = date("n", strtotime($createdate));
            $rok = date("Y", strtotime($createdate));
            $godzina = date("H", strtotime($createdate));
            $minuta = date("i", strtotime($createdate));

            $miesiac_pl = array(1 => 'Stycznia', 'Lutego', 'Marca', 'Kwietnia', 'Maja', 'Czerwca', 'Lipca', 'Sierpnia', 'Września', 'Października', 'Listopada', 'Grudznia');

            $status = $row['accepted'];


            $class = (i%2==0) ? 'tr-userlist-1' : 'tr-userlist-2';

            echo "<tr class='$class'>";
            echo "<th class='th-username'>$opponent</th>";
            echo "<th class='th-username'>$name $surname</th>";
            echo "<th class='th-username'>$dzien $miesiac_pl[$miesiac] $rok, $godzina:$minuta</th>";
            echo "<th class='th-name-userlist'>Oczekujące</th>";
            echo "<th class='th-name-userlist'><font onclick='cancelChallange($opponent_id)'>Anuluj</font></th>";
            echo "</tr>";

        }

        echo '</table><br><br>';
    }


    $stmt = $conn->prepare("SELECT DISTINCT * FROM challanges, users WHERE host_id=users.id AND opponent_id=? AND accepted=0");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $their_challanges = $stmt->get_result();

	// Wyzwanie rzucone nam
    if($their_challanges->num_rows > 0) {
        echo '<br><font class="page-title">Rzucone mi wyzwania</font><br>';


        echo '<table id="table-userlist">';
            echo '<tr id="tr-userlist-title">';
                echo '<th class="th-username">Przeciwnik</th>';
                echo '<th class="th-username">Data utworzenia</th>';
                echo '<th class="th-name-userlist"></th>';
                echo '<th class="th-name-userlist"></th>';
            echo '</tr>';
        for($i = 0; $i < $their_challanges->num_rows; $i++)
        {
            $row = $their_challanges->fetch_assoc();
            $opponent = $row['username'];
            $createdate = $row['created_date'];
			$host = $row['host_id'];


            $dzien = date("j", strtotime($createdate));
            $miesiac = date("n", strtotime($createdate));
            $rok = date("Y", strtotime($createdate));
            $godzina = date("H", strtotime($createdate));
            $minuta = date("i", strtotime($createdate));

            $miesiac_pl = array(1 => 'Stycznia', 'Lutego', 'Marca', 'Kwietnia', 'Maja', 'Czerwca', 'Lipca', 'Sierpnia', 'Września', 'Października', 'Listopada', 'Grudznia');

            $status = $row['accepted'];

            $class = (i%2==0) ? "tr-userlist-1" : "tr-userlist-2";

            echo "<tr class='$class'>";
            echo "<th class='th-username'>$opponent</th>";
            echo "<th class='th-username'>$dzien $miesiac_pl[$miesiac] $rok, $godzina:$minuta</th>";
            echo "<th class='th-name-userlist'><font onclick='denyChallange($host)'>Anuluj</font></th>";
            echo "<th class='th-name-userlist'><font onclick='acceptChallange($host)'>Akceptuj</font></th>";
            echo "</tr>";
        }

        echo '</table><br><br>';
    }


	$sql = "SELECT a.player1,
		CONCAT(bp.Name, ' ', bp.surname) AS player1_name,
		bp.username as user,
		bp.number AS numer,
		bp.email AS mail,
        a.player2,
        CONCAT(bc.Name, ' ', bc.surname) AS player2_name,
		bc.username as user2,
		bc.number AS numer,
		bc.email AS mail,
		a.create_date,
		a.id
        FROM matches AS a
            INNER
				JOIN users AS bp
                ON bp.ID = a.player1
            INNER
				JOIN users AS bc
                ON bc.ID = a.player2
        WHERE finished = 0 AND (player2=$userId XOR player1=$userId)";
	$accepted_challenges = $conn->query($sql);


    if ($accepted_challenges->num_rows > 0)
    {
		echo '<font class="page-title">Oczekujące mecze</font><br>';

        echo '<table id="table-userlist">';
        echo '<tr id="tr-userlist-title">';
        echo '<th class="th-username">Przeciwnik</th>';
		echo '<th class="th-username" style="width: 15%;">Data utworzenia</th>';
		echo '<th class="th-username" style="width: 10%;">Numer telefonu</th>';
		echo '<th class="th-username">Podaj wynik</th>';
		echo '</tr>';
		for($i = 0; $i < $accepted_challenges->num_rows; $i++)
		{

			$row = $accepted_challenges->fetch_assoc();

			if($row['player1'] == $userId)
			{
				$opponent = $row['player2_name'];
				$number = $row['numer'];
				$opponentId = $row['player2'];
				$num = 1;
				$p = "";
				$t = "2";
			}
			else if($row['player2'] == $userId)
			{
				$opponent = $row['player1_name'];
				$number = $row['numer'];
				$opponentId = $row['player1'];
				$num = 2;
				$p = "2";
				$t = "";
			}
			$username = $row["user$t"];

			$matchId = $row['id'];
			$didPlayerSentResult = $conn->query("SELECT * FROM matches WHERE id=$matchId AND player1_score$p <> -1 AND player2_score$p <> -1 AND finished=0");
			$thisMatch = $didPlayerSentResult->fetch_assoc();


			if($CONFIG['debug'] == true) {
				print_r($row);
			}

			$player1_name = $row['player1_name'];
			$player2_name = $row['player2_name'];
			$createdate = $row['create_date'];


			$class = ($i % 2 == 0 ? "tr-userlist-1" : "tr-userlist-2");
			$test = $row['player1_name'];



			echo "
			<tr class='$class'>
			<th class='th-username'>$username - $opponent</th>
			<th class='th-username'>$createdate</th>
			<th class='th-username'>$number</th>
			";

			if($didPlayerSentResult->num_rows > 0)
			{
				$p1score = $thisMatch["player1_score$p"];
				$p2score = $thisMatch["player2_score$p"];

				$p1score2 = $thisMatch["player1_score$t"];
				$p2score2 = $thisMatch["player2_score$t"];



				echo "
				<th class='th-username'>
				<font>$p1score <font style='font-family: Arial'>:</font> $p2score</font> - ";

				if($p1score2 == -1 && $p2score2 == -1)
					print "<font style='color: #cccc00'>oczekiwanie na przeciwnika</font>";
				else if($p1score == $p1score2 && $p2score == $p2score2)
					print "<font style='color: #5dbd00'>wyniki zgodne</font>";
				else
					print "<font style='color: red'>wyniki niezgodne</font>";

			}

			else
			{
				echo "
				<th class='th-name-userlist'>
				<input type='text' placeholder='Wynik $player1_name' class='input-findoponent scoreinput' minlength='1' maxlength='2' type='number' name='wynik1-$i' id='wynik1-$i' style='width:46%; float: left; margin-right: 4px;' />
				<input type='text' placeholder='Wynik $player2_name' class='input-findoponent scoreinput' minlength='1' maxlength='2' type='number' name='wynik2-$i' id='wynik2-$i' style='width:46%; float: left;' />
				<font class='submitscore' onclick='sendResult($i, $opponentId, $num)'>Potwierdź</font>";
			}

			echo "
				</th>
				</tr>";
		}

		echo '</table>';
    }
    else
        echo "Brak";


    $conn->close();


?>
