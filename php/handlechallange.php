<?php 
session_start();
/////////// HOST TO OSOBA WYZYWAJĄCA A TARGET TO OSOBA WYZYWANA ////////////

// POST zawiera: type i opponent_id
if( isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] != 'guest' && isset($_POST['type']) && isset($_POST['opponent_id']) && is_numeric($_POST['opponent_id']) )
{

require_once($_SESSION['rootPath'] . "/db_config.php");
require_once($_SESSION['rootPath'] . "/config.php");

$conn = new mysqli($db_host, $db_user, $db_password, $db_database);
$conn->query('SET NAMES utf8');

/////////// DANE /////////////////////
$target_id = $_POST['opponent_id'];	// On rzucil nam wyzwanie
$host_id = $_SESSION['userID'];	 	// My
//////////////////////////////////////

//SQL Queries
$SQL['howManyActiveMatchesHostHave'] = "SELECT id FROM matches WHERE (player1=$host_id XOR player2=$host_id) and finished=0";	//Sprawdzenie czy host nie osiągnął maksymalnej liczby meczy okreslonej w configu

$SQL['howManyActiveMatchesTargetHave'] = "SELECT id FROM matches WHERE (player1=$target_id XOR player2=$target_id) and finished=0";			//Sprawdzenie czy target nie osiągnął maksymalnej liczby meczy aktywnych okreslonej w configu

$SQL['doesActiveMatchAlreadyExist'] = "SELECT id FROM matches WHERE ((player1=$target_id AND player2=$host_id) OR (player2=$target_id AND player1=$host_id)) AND finished=0";	//Czy juz istnieje aktywny mecz miedzy graczami

$SQL['howManyMatchesBetweenPlayers'] = "SELECT id FROM matches WHERE ((player1=$target_id AND player2=$host_id) OR (player2=$target_id AND player1=$host_id)) AND finished=1"; 	// Ile meczy zagrali juz ci gracze miedzy sobą

$SQL['doesChallangeAlreadyExist'] = "SELECT id FROM challanges WHERE (host_id=$host_id AND opponent_id=$target_id) OR (opponent_id=$host_id AND host_id=$target_id)";		//Sprawdzenie czy wyzwanie już istnieje

$SQL['createChallange'] = "INSERT INTO challanges VALUES ('$host_id', '$target_id', CURRENT_TIMESTAMP, '0')";		//Utworzenie wyzwania w bazie danych

$SQL['denyChallange'] = "DELETE FROM challanges WHERE host_id=? AND opponent_id=?";		// Odrzuć wyzwanie i usuń je z bazy (SQL W POSTACI PREPARED STATEMENT)


//Sprawdzenie czy przeciwnik nie osiągnął maksymalnej liczby meczy aktywnych okreslonej w configu



// Wyzwanie przeciwnika na pojedynek
if( $_POST['type'] == "challange" )
{
	//Sprawdzanie czy wyzwanie nie jest rzucone samemu sobie
    if($host_id == $target_id)
    {
        print "Nie możesz wyzwać sam siebie!";
        return;
    }	
	
	
	$howManyMatchesHostHave = $conn->query( $SQL['howManyActiveMatchesHostHave'] );
	if($howManyMatchesIHave->num_rows >= $CONFIG['maxNumberOfActiveMatches'])
	{
		print "Osiągnieto maksymalną liczbę meczy";
		return;
	}
	
	
	
	$howManyActiveMatchesTargetHave = $conn->query( $SQL['howManyActiveMatchesTargetHave'] );
	if($howManyActiveMatchesTargetHave->num_rows >= $CONFIG['maxNumberOfActiveMatches'])
	{
		print "Przeciwnik nie może zagrać obecnie meczy ponieważ osiągnął maksymalną liczbę aktywnych meczy";
		return;
	}
	
	
	
	$doesActiveMatchAlreadyExist = $conn->query( $SQL['doesActiveMatchAlreadyExist'] );
	if($doesActiveMatchAlreadyExist->num_rows > 0)
	{
		print "Mecz miedzy wami już istnieje";
		return;
	}
	
	
	
	$howManyMatchesBetweenPlayers = $conn->query( $SQL['howManyMatchesBetweenPlayers'] );
	if($howManyMatchesBetweenPlayers->num_rows > $CONFIG['maxNumberOfMatchesBetweenPlayers'])
	{
		print "Nie możecie zagrać ponieważ zagraliście maksymalną liczbę gier między wami";
		return;
	}
	
	
	
	$doesChallangeAlreadyExist = $conn->query( $SQL['doesChallangeAlreadyExist'] );
	if($doesChallangeAlreadyExist->num_rows > 0)
	{
		print "To wyzwanie już jest rzucone";
		return;
	}
	
	
	
	if ($conn->query( $SQL['createChallange'] ) == true) {
		
		print "success";
	}
	else {
		
		print "Error: " . $sql . "<br>" . $conn->error;
	}
	
}///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// Odrzucanie wyzwania skierowanego do nas
if( $_POST['type'] == "denychallange" )
{
	
	
	if($stmt = $conn->prepare( $SQL['denyChallange'] )) {
		
		$stmt->bind_param("ii", $_POST['opponent_id'], $host_id);
		$stmt->execute();
		
		$stmt->close();
		
		print "$success";
	}
	else {
		print "Błąd";
	}
}///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



// Przyjecie wyzwania i tworzenie meczu
if( $_POST['type'] == "acceptchallange" )
{
	
	$howManyMatchesHostHave = $conn->query( $SQL['howManyActiveMatchesHostHave'] );
	if($howManyMatchesIHave->num_rows >= $CONFIG['maxNumberOfActiveMatches'])
	{
		print "Nie możesz zagrać obecnie meczu ponieważ osiągnąłeś maksymalną liczbę aktywnych meczy";
		return;
	}
	
	$howManyActiveMatchesTargetHave = $conn->query( $SQL['howManyActiveMatchesTargetHave'] );
	if($howManyActiveMatchesTargetHave->num_rows >= $CONFIG['maxNumberOfActiveMatches'])
	{
		print "Przeciwnik nie może zagrać obecnie meczy ponieważ osiągnął maksymalną liczbę aktywnych meczy";
		return;
	}
	
	
	//Czy juz istnieje aktywny mecz miedzy graczami
	$doesActiveMatchAlreadyExist = $conn->query( $SQL['doesActiveMatchAlreadyExist'] );
	if($doesActiveMatchAlreadyExist->num_rows > 0)
	{
		print "Mecz miedzy wami już istnieje";
		return;
	}
	
	
	$howManyMatchesBetweenPlayers = $conn->query( $SQL['howManyMatchesBetweenPlayers'] );
	if($howManyMatchesBetweenPlayers->num_rows > $CONFIG['maxNumberOfMatchesBetweenPlayers'])
	{
		print "Nie możecie zagrać ponieważ zagraliście maksymalną liczbę gier między wami";
		return;
	}
	
	
	$sql = "SELECT * FROM challanges WHERE host_id=? AND opponent_id=?";	//Sprawdzamy czy wyzwanie nadal istnieje przed przyjeciem
	if($stmt = $conn->prepare($sql)) {
		
		$stmt->bind_param("ii", $_POST['opponent_id'], $host_id);
		$stmt->execute();
		
		$result = $stmt->get_result();
		
		if(!($result->num_rows > 0))
		{
			print "Nieznaleziono wyzwania.";
			return;
		}
		$stmt->close();
		
		//Usun wyzwanie i stworz mecz
		$sql = "DELETE FROM challanges WHERE host_id=? AND opponent_id=?";
		$stmt2 = $conn->prepare($sql);
		$stmt2->bind_param("ii", $_POST['opponent_id'], $host_id);
		$stmt2->execute();
		$stmt2->close();
		
		

		$sql = "INSERT INTO matches (`player1` ,`player2` ,`create_date` ,`player1_score` ,`player2_score` ,`end_date` ,`finished`) VALUES (?,  ?, CURRENT_TIMESTAMP ,  DEFAULT,  DEFAULT, NULL ,  '0')";
		$stmt3 = $conn->prepare($sql);
		$stmt3->bind_param("ii", $_POST['opponent_id'], $host_id);
		$stmt3->execute();
		$stmt3->close();
		
		
		print "success";
	}
	else {
		print "Błąd";
	}
}//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



// Anuluj rzucone wyzwanie
if( $_POST['type'] == "cancelchallange" )
{
	$host_id = $_SESSION['userID'];
	$sql = "DELETE FROM challanges WHERE host_id=? AND opponent_id=?";
	if($stmt = $conn->prepare($sql)) {
		
		$stmt->bind_param("ii", $host_id, $_POST['opponent_id']);
		$stmt->execute();
		
		//print_r($stmt->error_list);
		
		$stmt->close();
		
		print "success";
	}
	else {
		print "Błąd";
	}
	
}///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



$conn->close();
}
?>