<?php

session_start();
require_once("../db_config.php");

?>

<div id="rules" class="page">

<?php
	$conn = $GLOBALS['db_conn'];
	$result = $conn->query("SELECT html FROM pages WHERE page='rules'");

	if($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();

		print html_entity_decode($row['html']);
	}
	else {
		print "error";
	}
?>

</div>

<?php

if($_SESSION['mainAdmin'] == true)
{
	print "<br><button id='editpage' onclick='editpage()'>Edytuj strone</button>";
}
?>
