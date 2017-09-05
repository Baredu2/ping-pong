<?php session_start();
require_once('../db_config.php');

?>

<?php	if($_SESSION['accessLevel'] == 'admin') { ?>

	<table id="addpost-table">
		<tr><th class="login-title">Zaktualizuj mecze</th></tr>
		<tr><th>Manualnie wywołaj aktualizacje oczekujących meczu</th></tr>
		<tr><th><input class="login-input" style='width: 30%;' type="submit" value="Aktualizuj" name="submit" onclick="callCron()"/></th></tr>
	</table>


	<?php	if($_SESSION['accessLevel'] == 'admin' && $_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == strtolower("administrator")) { ?>

	<br><table id="addpost-table">
		<tr><th class="login-title">Restart bazy danych(Tylko głowny administrator)</th></tr>
		<tr><th>Domyślnie tylko reset statystyk graczy</th></tr>
		<tr><th class="th-deletematches">
			<input type="checkbox" name="deletematches" id="deletematches" value="deletematches">Usunąć mecze?<br>
		</th></tr>
		<tr><th class="th-deleteusers">
			<input type="checkbox" name="deleteusers" id="deleteusers" value="deleteusers">Usunąć użytkowników?<br>
		</th></tr>
		<tr><th class="th-deleteadmins">
			<input type="checkbox" name="deleteadmins" id="deleteadmins" value="deleteadmins">Usunąć dodatkowych administratorów?<br>
		</th></tr>
		<tr><th><input class="login-input" style='width: 30%;' type="submit" value="Wykonaj" name="submit" onclick="databaseReset()"/></th></tr>
	</table>

	<?php
		$conn = $GLOBALS['db_conn'];



		$getSettingsSql = "SELECT * FROM settings";
		$getSettings = $conn->query($getSettingsSql);

		if( $getSettings->num_rows > 0 )
		{

			print "
			<form id='settings'>
			<table id='table-userlist'>
			<tr id='tr-userlist-title' style='width: 100%;'>
				<th class='th-username' style='width: 70%;'>Opis</th>
				<th class='th-username' style='width: 30%;'>Wartość</th>
			</tr>";


			for($i = 0; $i < $getSettings->num_rows; $i++)
			{
				$class = ( $i % 2 == 0 ? "tr-userlist-1" : "tr-userlist-2");
				$row = $getSettings->fetch_assoc();

				//$inputType['boolean'] = "type='checkbox' " . ($row['value'] ? "checked" : "");
				$inputType['integer'] = "type='number'";
				$inputType['date'] = "type='date'";

				$type = $inputType[$row['type']];

				print "
				<tr class='$class'>
					<th class='th-username'>$row[description]</th>
					<th class='th-username'><input style='text-align: center;' name='$row[name]' $type value='$row[value]' /></th>
				</tr>";
			}


			print "</table></form><br><center>
			<input class='login-input' style='width: 30%;' id='savesettings-button' type='submit' value='Zapisz ustawienia' /></center>";

		}
		else
		{
			print "Brak ustawień w bazie";
		}
	}
}
?>

<script>



function callCron()
{

	$.post("cron.php", {}, function(result) {

		alertify.success("Zaktualizowano!");

		if(result != '') {
			console.log(result);
			alertify.error(result);
		}

		$('#content').load('pages/adminpanel.php');
	});
}

<?php	if($_SESSION['accessLevel'] == 'admin' && $_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == strtolower("administrator")) { ?>



$('#deletematches').change(function() {

	if($(this).is(":checked")) {

		return;
	}

	$('#deleteusers').attr('checked', false);
	$('#deleteadmins').attr('checked', false);
});

$('#deleteusers').change(function() {

	if($(this).is(":checked")) {

		$('#deletematches').attr('checked', true);
		return;
	}

	$('#deleteadmins').attr('checked', false);
});

$('#deleteadmins').change(function() {

	if($(this).is(":checked")) {

		$('#deletematches').attr('checked', true);
		$('#deleteusers').attr('checked', true);
		return;
	}


});

function databaseReset()
{
	alertify.prompt("Podaj hasło do konta Administratora", function(e, password) {

		if(password.length < 1)
			alertify.error("Hasło nie może być puste");

		else if(e) {

			var deletematches = $('#deletematches').is(':checked');
			var deleteusers = $('#deleteusers').is(':checked');
			var deleteadmins = $('#deleteadmins').is(':checked');

			console.log(String(deletematches) + String(deleteusers) + String(deleteadmins));

			$.post("php/databasereset.php", {"password": password, "deletematches": deletematches, "deleteusers": deleteusers, "deleteadmins": deleteadmins}, function(result) {

				//alertify.success(result);
				console.log(result);

				if(result == "success")
					alertify.success("Statystyki graczy zostały zresetowane");

				else {
					alertify.error(result);
					console.log(result);
				}

				$('#content').load('pages/adminpanel.php');
			});
		}
	}, "");
}

$('#savesettings-button').click(function() {

	var form = $('#settings').serialize();
	console.log(form);

	$.post( "php/setSettings.php", form, function(result) {

		if(result == "success")
		{
			alertify.success("Ustawienia zostały zapisane");
			$('#content').load('pages/adminpanel.php');
		}
		else
		{
			alertify.error(result);
			console.log(result);
		}
	});
});

<?php } ?>



</script>
