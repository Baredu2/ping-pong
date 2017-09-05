<?php 
session_start(); 
require_once( $_SESSION['rootPath'] . '/db_config.php' );
?>
<font class="page-title">FAQ</font>
<div id="faq" class="page">

<?php
	$conn = $GLOBALS['db_conn'];
	$result = $conn->query("SELECT html FROM pages WHERE page='faq'");
	
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

