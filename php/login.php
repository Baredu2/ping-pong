<?php
session_start();


if (!isset($_POST['username']) || !isset($_POST['password']))
{
	// if (!isset($_POST['submit']))
		// echo 'submit';
	// if (!isset($_POST['username']))
		// echo 'username';
	// if (!isset($_POST['password']))
		// echo 'password';
	// if (!isset($_POST['user']))
		// echo 'user';

	header('Location: ../index.php');
	exit();
}


	require_once($_SESSION['rootPath'] . "/db_config.php");



	//Łączenie z bazą danych
	$conn = $conn = $GLOBALS['db_conn'];


	//Pobranie usera i hasła z posta
	$username = htmlspecialchars($_POST['username'], ENT_QUOTES, "UTF-8");
	$password = htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");


	//Sprawdzanie czy user istnieje w bazie
	$findUserSql = sprintf("SELECT * FROM users WHERE username='%s'", $username);
	$findUser = $conn->query($findUserSql);


	if ($findUser->num_rows == 1)
	{
		//Pobranie i sprawdzenie czy zahashowane hasła się zgadzają
		$userDetails = $findUser->fetch_assoc();
		if($CONFIG['disableLogin'] == true && $userDetails['access'] == "user")
		{
			echo "Logowanie użytkownikow jest wyłączone";
			return;
		}

		if (password_verify($password, $userDetails['password']))
		{
			if($userDetails['blocked'] == 1)
			{
				echo "To konto jest zablokowane. Po wiecej informacji udaj sie do administratora";
				return;
			}

			$_SESSION['user'] = $userDetails['username'];
			$_SESSION['accessLevel'] = $userDetails['access'];
			$_SESSION['userID'] = $userDetails['id'];
			$_SESSION['lastlogin'] = $userDetails['lastlogin'];

			if($_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == "administrator")
				$_SESSION['mainAdmin'] = true;
			else
				$_SESSION['mainAdmin'] = false;
		}
		else
			echo 'Niepoprawny login lub hasło';

	}
	else
	{
		echo 'Niepoprawny login lub hasło';
	}

	$conn->close();
	//header("Location: ../index.php");

?>
