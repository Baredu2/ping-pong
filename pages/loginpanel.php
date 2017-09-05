
<?php 
if(!isset($_SESSION['user'])) { 
?>
	<form method="post" target="void">
		<table>
			<tr><th class="login-title">LOGOWANIE</th></tr>
			<tr><th><input class="login-input login-username" type="text" name="username" placeholder="Podaj swój login" autofocus/></th></tr>
			<tr><th><input class="login-input login-password" type="password" name="password" placeholder="Podaj swoje hasło"/></th></tr>
			<tr><th><input class="login-input login-submit" type="submit" value="Zaloguj" name="submit"/></th></tr>
		</table>
	</form>

<?php } 
else { ?>

	<table>
		<tr><th class="login-title">ZALOGOWANY JAKO </th></tr>
		<tr><th class="login-text-other name"><i class="icon-user-1" style="position: relative; top: -3px;"></i><?php echo $_SESSION['user'] ?></th></tr>
		<!--<tr><th class="login-text-other">Typ konta: <?php echo $_SESSION['accessLevel'] ?></th></tr>-->
		<tr><th><a><div id="logout-button" onclick="LoadFile('myaccount', '#content')">Moje konto</div></a></th></tr>
		<tr><th><a href="php/logout.php"><div id="logout-button">Wyloguj się</div></a></th></tr>
	</table>
       
<?php } ?>

<script>
	$('.login-submit').click(function(event) {
		event.preventDefault();
		
		var username = $('.login-username').val();
		var password = $('.login-password').val();
	
		$.post("php/login.php", {"username": username, "password": password}, function(result) {
			if (result.trim().length == 0 )
				window.location.replace('');
			else {
				$('.login-password').val('');
				alertify.alert(result);
			}
		});
	});
	

</script>