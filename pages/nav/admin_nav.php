
<?php	if($_SESSION['accessLevel'] == 'admin') { 

	require_once($_SESSION['rootPath'] . "/php/conflictMatches.php");
	require_once($_SESSION['rootPath'] . "/db_config.php");
?>

<ul class="menu_ul admin-menu">
<li><a href="" class="load adduser" onclick="LoadFile('adduser', '#admin-options'), changetitle('Dodaj użytkownika - Tenis stołowy ZSE');" id="a2">Dodaj gracza</a></li>
<li><a href="" class="load addpost" onclick="LoadFile('addpost', '#admin-options'), changetitle('Dodaj post - Tenis stołowy ZSE');" id="a2">Dodaj post</a></li>
<li><a href="" class="load userlist" onclick="LoadFile('userlist', '#admin-options'), changetitle('Lista użytkowników - Tenis stołowy ZSE');" id="a2">Lista użytkowników</a></li>
<li><a href="" class="load toapprove" onclick="LoadFile('toapprove1', '#admin-options'), changetitle('Mecze do zaakceptowania - Tenis stołowy ZSE');" id="a2">Meczy do potwierdzenia<font style="font-family: Arial">:</font> <font class="conflictMatches">0</font></a></li>
<li><a href="" class="load adminpanel" onclick="LoadFile('adminpanel', '#admin-options'), changetitle('Panel Administratora - Tenis stołowy ZSE');" id="a2">Panel admina</a></li>
<li><a href="" class="load activematches" onclick="LoadFile('activematches', '#admin-options'), changetitle('Aktywne mecze - Tenis stołowy ZSE');" id="a2">Aktywne mecze</a></li>
</ul>


<?php

 } ?>