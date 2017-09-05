<?php

session_start();
require_once('../db_config.php');
error_reporting(-1);
ini_set('display_errors', 'On');
$userId = $_SESSION['userID'];

if($_SESSION['accessLevel'] == 'user' ||
          $_SESSION['accessLevel'] == 'admin' ) { ?>

<font class="page-title">Moje konto</font>
<section class="account-content">
<?php
$checkMatch = mysql_select_single("SELECT CONCAT(name, ' ', surname) AS fullname FROM users WHERE id=$_SESSION[userID] LIMIT 1");
print $checkMatch['fullname'];
 ?>


<form method="post" onsubmit="return validateForm('adduser-form');">
	<table id="adduser-table">
		<tr>
			<th></th>
			<th class="login-title onethird">Zmień hasło</th>
			<th class="onethird"></th>
		</tr>

		<tr>
			<th class="label"><label for="password">Obecne hasło</label></th>
			<th><input class="login-input username adduser" type="password" name="username" placeholder="" required></th>
			<th></th>
		</tr>

		<tr>
			<th class="label"><label for="newpassword">Nowe hasło</label></th>
			<th><input class="login-input newpassword adduser" type="password" name="newpassword" placeholder="" required /></th>
			<th></th>
		</tr>

		<tr>
			<th class="label"><label for="repeatnewpassword">Nowe hasło</label></th>
			<th><input class="login-input newpassword2 adduser" type="password" name="newpassword2" placeholder="" autocomplete="off" required/></th>
			<th></th>
		</tr>

		<tr>
			<th></th>
			<th><input class="login-input submit adduser" type="submit" value="Zmień" name="submit" onclick=""/></th>
			<th></th>
		</tr>
	</table>
</form>


<?php


if($_SESSION['accessLevel'] == 'admin') {

	print '<font class="page-title">Konta z uprawnieniami administratora nie mogą rozgrywać meczy</font><br>';
	return;
}

$sql = "SELECT a.player1,
	CONCAT(bp.Name, ' ', bp.surname) AS player1_name,
	bp.number AS numer,
	bp.email AS mail,
	a.player2,
	CONCAT(bc.Name, ' ', bc.surname) AS player2_name,
	bc.number AS numer,
	bc.email AS mail,
	a.create_date,
	a.id,
	a.finished,
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
	WHERE (player2=$userId XOR player1=$userId)
	ORDER BY end_date DESC, create_date DESC";

$conn = new mysqli($db_host, $db_user, $db_password, $db_database);
$conn->query('SET NAMES utf8');
$allMatches = $conn->query($sql);


if ($allMatches->num_rows > 0)
{
	echo '<font class="page-title">Historia meczy</font><br>';

	echo '<table id="table-userlist">';
			echo '<tr id="tr-userlist-title">';
				echo '<th class="th-username">Przeciwnik</th>';
				echo '<th class="th-username">Data zakończenia</th>';
				echo '<th class="th-username">Wynik</th>';
				echo '<th class="th-username">Zakończony</th>';
			echo '</tr>';
		for($i = 0; $i < $allMatches->num_rows; $i++)
		{

			$row = $allMatches->fetch_assoc();

			if($row['player1'] == $userId)
			{
				$opponent = $row['player2_name'];
				$opponentId = $row['player2'];
				$num = 1;
				$p = "";
			}
			else if($row['player2'] == $userId)
			{
				$opponent = $row['player1_name'];
				$number = $row['numer'];
				$opponentId = $row['player1'];
				$num = 2;
				$p = "2";
			}

			$finished = ($row['finished'] == 1 ? "Zakończony" : "Nie zakończony");
			$end_date = $row['end_date'];
			$class = ($i % 2 == 0 ? "tr-userlist-1" : "tr-userlist-2");

			$p1_score = $row["player1_score$p"];
			$p2_score = $row["player2_score$p"];

			echo "
			<tr class='$class'>
			<th class='th-username'>$opponent</th>
			<th class='th-username'>$end_date</th>
			<th class='th-username'>$p1_score <font style='font-family: Arial'>:</font> $p2_score</th>
			<th class='th-username'>$finished</th>
			</th>
			</tr>";

		}

		echo '</table>';


}

$conn->close();
?>

</section>


<?php } ?>

<script>

function toggle_password() {
	var x = document.getElementById("checkbox").checked;
	document.getElementById("newpwd").type = (x ? "text" : "password");
	document.getElementById("oldpwd").type = (x ? "text" : "password");
}

$('.submit').click(function(event) {
	event.preventDefault();
	var password = $("input[name='password'].adduser").val();
	var newpassword = $("input[name='newpassword'].adduser").val();
	var newpassword2 = $("input[name='newpassword2'].adduser").val();

	alertify.success(password);

	if(password.trim() == '' ||
		newpassword.trim() == '' ||
		newpassword2.trim() == '')
	{
		alertify.error("Wszystkie pola muszą zostać wypełnione");
		return;
	}

	if( password != password2 )
	{
		alertify.error("Nowe hasła się nie zgadzają");
		return;
	}


	$.post("php/changepassword.php", {"password": password, "newpassword": newpassword}, function(result) {


		if(result == "success")
		{
			alertify.success("Zmieniono hasło");
			$("input[name='password'].adduser").val('');
			$("input[name='newpassword'].adduser").val('');
			$("input[name='newpassword2'].adduser").val('');
		}
		else
		{
			alertify.error(result);
		}

		console.log(result);
	});
});


</script>
