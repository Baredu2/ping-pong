<?php
session_start();
?>

<ul id="menu" class="menu_ul standard-menu">

<?php
require($_SESSION['rootPath'] . '/pages/nav/guest_nav.php');

switch($_SESSION['accessLevel']) {       
	case 'user':
		require_once($_SESSION['rootPath'] . '/pages/nav/guest_nav.php');
		require($_SESSION['rootPath'] . '/pages/nav/user_nav.php');
		break;

	case 'admin':
		require_once($_SESSION['rootPath'] . '/pages/nav/guest_nav.php');
		require_once($_SESSION['rootPath'] . '/pages/nav/user_nav.php');
		require_once($_SESSION['rootPath'] . '/pages/nav/admin_nav.php');
}

?>

</ul>