<?php 
session_start();

if($_SESSION['accessLevel'] == 'admin' && $_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == strtolower("administrator")) { 
	
	require($_SESSION['rootPath'] . "/db_config.php");
	$conn = new mysqli($db_host, $db_user, $db_password, $db_database);
	$conn->query('SET NAMES utf8');
	
	if( isset($_POST['password']) && 
		isset($_POST['deletematches']) &&
		isset($_POST['deleteusers'])  &&
		isset($_POST['deleteadmins'])) {
			
			$password = htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");
			$checkAdminPasswordSql = "SELECT password FROM users WHERE username='administrator' AND id=1";
			$checkAdminPassword = $conn->query($checkAdminPasswordSql);
			
			if ($checkAdminPassword->num_rows == 1)
			{
				//Pobranie i sprawdzenie czy zahashowane hasła się zgadzają
				$userDetails = $checkAdminPassword->fetch_assoc();

				if (password_verify($password, $userDetails['password']))
				{
					
					$p1 = $_POST['deletematches'];
					$p2 = $_POST['deleteusers'];
					$p3 = $_POST['deleteadmins'];
					
					$deleteAllUsersWithAdminsSql = "DELETE FROM users WHERE id <> 1";
					$deleteOnlyUsersSql = "DELETE FROM users WHERE access NOT LIKE 'admin'";
					
					$deleteChallangesSql = "DELETE FROM challanges";
					$deleteMatchesSql = "DELETE FROM matches";
					$resetUserStatisticsSql = "UPDATE users SET matches=0, wins=0, losses=0, score=0";
					
					
					if($p3 == "true") {
						
						$conn->query($deleteAllUsersWithAdminsSql);
					}
					if($p2 == "true") {
						
						$conn->query($deleteOnlyUsersSql);
					}
					if($p1 == "true") {
						
						$conn->query($deleteChallangesSql);
						$conn->query($deleteMatchesSql);
					}
					
					
					
					$conn->query($resetUserStatisticsSql);
					
					print "success";
				}
				else
					echo 'Błędne hasło';

			}
			else {
				
				print "Problem z znalezieniem administratora głownego";
			}
			
		}
		else {
			print "Błędne dane wejściowe";
		}
	
}
else {
	print "Brak uprawnień";
}

$conn->close();

?>