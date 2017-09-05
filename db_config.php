<?php

$db_host		= 'hasanciftci.home.pl';
$db_user		= '06687094_tenis';
$db_password	= 'zaq1@WSX';
$db_database	= '06687094_tenis';

$db_error_msg	= 'Nie udało połączyć sie z bazą danych';

$db_debugging	= true;



$GLOBALS['db_conn'] = new mysqli($db_host, $db_user, $db_password, $db_database);
$GLOBALS['db_conn']->query('SET NAMES utf8');

$conn = $GLOBALS['db_conn'];
$settings = $conn->query('SELECT name, value FROM settings');

if( $settings->num_rows > 0 )
{
	while( $row = $settings->fetch_assoc() )
	{
		$CONFIG[$row['name']] = $row['value'];
	}
}



function mysql_select_single($query)
{
	global $conn;
	$result = mysqli_query($conn, $query) or die(var_dump($query)."<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>select_single</b> (select single row from database)<br><br>".mysqli_error($connect));
	$row = $result->fetch_assoc();

	return !empty($row) ? $row : false;
}

?>
