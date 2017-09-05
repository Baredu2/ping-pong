<?php
session_start();

if($_SESSION['accessLevel'] != 'admin') {

	header('Location: ../index.php');
	exit();
}

if($_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == "administrator") {
	$isMainAdmin = true;
}
else {
	$isMainAdmin = false;
}

?>
<font class="page-title">Lista użytkowników</font>

<?php
    /*ini_set("display_errors", 0);*/

    require_once($_SESSION['rootPath'] . "/db_config.php");
	$conn = $GLOBALS['db_conn'];




	if($isMainAdmin)
		$listPlayersSql = "SELECT id, username, email, name, surname, number, access, blocked FROM users ORDER BY access ASC, username ASC";
	else
		$listPlayersSql = "SELECT id, username, email, name, surname, number, access, blocked FROM users WHERE access NOT LIKE 'admin' ORDER BY access ASC, username ASC";


	$playerList = $conn->query($listPlayersSql);
    if($playerList->num_rows > 0)
    {
        echo '<table id="table-userlist">';
		echo '<tr id="tr-userlist-title">';
		echo '<th class="th-lp">L.p.</th>';
		echo '<th class="th-username">Nick</th>';
		echo '<th class="th-name-userlist">Imię</th>';
		echo '<th class="th-surname">Nazwisko</th>';
		echo '<th class="th-email">E-m@il</th>';
		echo '<th class="th-telephon">Telefon</th>';
		echo '<th class="th-access">Typ konta</th>';
		echo '<th class="th-icons">Edytuj</th>';
		echo '</tr>';

        for($i = 0; $i < $playerList->num_rows; $i++)
        {
            $row = $playerList->fetch_assoc();
            $class = ($i % 2 == 0 ? "tr-userlist-1" : "tr-userlist-2");
			displayRow($class, $i + 1, $row['id'], $row['username'], $row['email'], $row['name'], $row['surname'], $row['number'], $row['access'], $row['blocked']);

        }

    }

    function displayRow($class, $lp, $id, $username, $email, $name, $surname, $number, $access, $blocked)
    {
        if($_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == "administrator")
        {
            if($access == 'admin')
            {
                $class = 'tr-userlist-admin';
            }
        }
        if($blocked == 1)
        {
            $class = 'tr-userlist-cannotplay';
        }
				$lock = ($blocked == 1 ?
				"<i class='icon-lock-open-alt tooltip' onclick='unblockuser($id, \"$username\")'><span class='tooltiptext tgh'>Odblokuj gracza $name $surname</span></i>" :
				"<i class='icon-lock tooltip' onclick='blockuser($id, \"$username\")'><span class='tooltiptext tgh'>Zablokuj gracza $name $surname</span></i>");

        echo "<tr class='$class'>";
        echo "<th class='th-lp'>$lp</th>";
        echo "<th class='th-username' id='th-username-$lp'>$username</th>";
        echo "<th class='th-name-userlist' id='th-name-$lp'>$name</th>";
        echo "<th class='th-surname' id='th-surname-$lp'>$surname</th>";

        echo "<th class='th-email'>
        <a class='tooltip' onclick='changeinfo($id, $lp, \"email\")'><font id='th-email-$lp'>$email</font><span class='tooltiptext'>Zmień email $name $surname</span></a>
        <a class='tooltip' href='mailto: $email'><i class='icon-mail' style='margin-left: 8px;'></i><span class='tooltiptext'>Wyślij email do $name $surname</span></a>
        </th>";

        echo "<th class='th-telephon' onclick='changeinfo($id, $lp, \"telefon\")'><a class='tooltip'><font id='th-telefon-$lp'>$number</font><span class='tooltiptext'>Zmień numer $name $surname</span></a></th>";
		echo "<th class='th-access' id='th-access'>$access</th>";
        echo "<th class='th-icons'>
                <a onclick='changepwdadminalert($id, $lp)'>
                    <i class='icon-key tooltip'><span class='tooltiptext tgh'>Zmień hasło $name $surname</span></i>
                </a>
                <i class='icon-cancel tooltip' onclick='deleteuser($id, \"$username\")'><span class='tooltiptext tgh'>Usuń konto $name $surname</span></i>";
        if($access != 'admin')
        {
            echo $lock;
        }
        echo "</th>";
        echo "</tr>";
    }


    $conn->close();

?>
<div style="display:none">
<form id="loginForm">
    <fieldset>
        <label> Username </label>
        <input type="text" value="Mohammad"/>

        <label> Password </label>
        <input type="password" value="password"/>

        <input type="submit" value="Login"/>
    </fieldset>
</form>
</div>
<script>


function changepwdadmin(id, name, surname) {
	document.getElementById("backgrounddimming").style.visibility = "visible";
	document.getElementById("changepwdadmin").style.visibility = "visible";
	document.getElementById("backgrounddimming").style.opacity = "1";
	document.getElementById("changepwdadmin").style.opacity = "1";

	document.getElementById("user_id").value = id;
	document.getElementById("pass-info").innerHTML = "Dla " + name + " " + surname;
}

function changepwdadminalert(id, lp)
{
	var name = $('#th-name-' + lp).html();
	var surname = $('#th-surname-' + lp).html();

	if(+id == 1)
	{
		alertify.error("Hasło konta administratora można zmienić w opcjach konta");
		return;
	}

	alertify.prompt("Zmiana hasła dla <br><br><b>" + name + " " + surname, function(e, str) {

		str = String(str);
		if(str.length < 3)
			alertify.error("Hasło musi mieć przynajmniej 3 znaki");
		else if(e) {
			$.post("php/newpassword-admin.php", {"id": id, "newpassword": str}, function(result) {
				//alertify.success(result);
				if(result == "success")
					alertify.success("Hasło użytkownika " + name + " " + surname + " zostało zmienione pomyślnie.");
				else if(result == "notfound")
					alertify.error("Nie znaleziono użytkownika");
				else if(result == "checkerror")
					alertify.error("Błąd danych lub sesji");
				else
					alertify.error("Nieznany błąd");
			});
		}
	}, "");
}

function deleteuser(id, username)
{
	alertify.confirm("Czy na pewno chcesz usunac konto uzytkownika <br>" + username, function (e) {
			if(e) {
				$.post("php/deleteuser.php", {"id": id}, function(result) {

					if(result == "success")
						alertify.success("Usunięto użytkownika " + username);
					else
						alertify.error(result);

					console.log(result);

					$('#content').load('pages/userlist.php');
			});
		}
	});


	$('#content').load('pages/userlist.php');
}

function changeinfo(id, lp, field)
{

	var value = $('#th-' + field + '-' + lp).html();
	var name = $('#th-name-' + lp).html();
	var surname = $('#th-surname-' + lp).html();


	if(field == "email") {

		var message = "Zmiana e-mailu";
	}
	else if(field == "telefon") {

		var message = "Zmiana numeru";
	}
	message = message + " dla <br><b>" + name + " " + surname + "</b><br><br>Obecny: <b><i>" + value;


	alertify.prompt(message, function(e, str) {

		str = String(str);
		if(!str || str.length === 0) {

			alertify.error("Pole nie może być puste!");
			return;
		}
		if(field == "telefon" && str.length != 9) {

			alertify.error("Numer telefonu musi mieć 9 cyfr!");
			return;
		}

		if(e) {

			$.post("php/changeplayerinfo.php", {"id": id, "type": field, "value": str}, function(result) {

				if(result == "success")
					alertify.success("Zmieniono dane pomyślnie");

				else if(result == "inputerror")
					alertify.error("Błąd danych wejściowych");

				else if(result == "unknowntype")
					alertify.error("Nieprawidłowy typ zmienianych danych");

				else
					alertify.error("Nieznany błąd");


				console.log(result);
				$('#content').load('pages/userlist.php');
			});

		}

	}, "");


}

function blockuser(id, username)
{
	alertify.confirm("Czy na pewno chcesz zablokowac konto uzytkownika <br>" + username, function (e) {
			if(e) {
				$.post("php/blockuser.php", {"id": id, "type": "block"}, function(result) {

					if(result == "success")
						alertify.success("Usunięto użytkownika " + username);
					else
						alertify.error(result);

					console.log(result);

					$('#content').load('pages/userlist.php');
			});
			$('#content').load('pages/userlist.php');
		}
	});
}

function unblockuser(id, username)
{
	alertify.confirm("Czy na pewno chcesz odblokowac konto uzytkownika <br>" + username, function (e) {
			if(e) {
				$.post("php/blockuser.php", {"id": id, "type": "unblock"}, function(result) {

					if(result == "success")
						alertify.success("Odblokowano użytkownika " + username);
					else
						alertify.error(result);

					console.log(result);

					$('#content').load('pages/userlist.php');
			});
			$('#content').load('pages/userlist.php');
		}
	});
}


</script>
