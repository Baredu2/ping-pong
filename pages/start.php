<?php
session_start();

require_once("../db_config.php");

echo '<section data-step="4" data-intro="W tej sekcji pojawiać będą się informacje publikowane przez administratorów serwisu.">
    <h1 class="start-title">';

// Tytuł na stronie głównej
if($_SESSION['accessLevel'] == 'admin' || $_SESSION['accessLevel'] == 'user')
{    
    $conn = $GLOBALS['db_conn'];
	$result = $conn->query("SELECT value FROM settings WHERE name='titleOnStartPageForLoggedIn'");    
}
else
{ 
    $conn = $GLOBALS['db_conn'];
	$result = $conn->query("SELECT value FROM settings WHERE name='titleOnStartPage'");    
}

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();

    print($row['value']);
}
else {
    print " ";
}

echo '</h1>';
// KONIEC Tytułu

// Leader
$conn = $GLOBALS['db_conn'];

$leaderSql = "SELECT name, surname FROM users WHERE access NOT LIKE 'admin' ORDER BY score DESC, wins DESC, matches DESC, losses ASC LIMIT 1";
$leader = $conn->query($leaderSql);

$row = $leader->fetch_assoc();

$name = $row['name'];
$surname = $row['surname'];

echo "  <p style='color: #2ab3d2; padding-bottom: 10px; margin: 0 auto 0 auto; text-transform: uppercase; font-size: 21px; font-weight: 700; text-align: center;'>Aktualnym liderem jest <font style='color: #050607;'>$name $surname</font>.</p>
        </section>";
// END Leader


// motto
echo "<p style='text-align: center; font-size: 17px;padding-bottom: 50px;'>"; 

$conn = $GLOBALS['db_conn'];
$result = $conn->query("SELECT value FROM settings WHERE name='mottoOnStartPage'");

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();

    print($row['value']);
}
else {
    print " ";
}

echo "</p>";
// end motto

require_once('../php/news.php');
?>