<?php
	echo "admin: " . password_hash("admin", PASSWORD_BCRYPT) . "<br>";
	echo "user: " . password_hash("user", PASSWORD_BCRYPT);
	
?>