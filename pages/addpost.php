<?php session_start();

 ?>
<?php	if($_SESSION['accessLevel'] == 'admin') { ?>
	
	<form id="addpost-form" method="post" action="../php/createpost.php">
        <table id="addpost-table">
            <tr><th class="login-title">Dodaj post</th></tr>
            <tr><th><input class="login-input" type="text" name="title" maxlength="50" placeholder="Tytuł postu (max 50 znaków)" autofocus/></th></tr>
            <tr><th><textarea class="login-input" id="textarea-id" type="text" name="message" height="400" placeholder="Treść postu"></textarea></th></tr>
            <tr><th><input class="login-input" type="submit" value="Dodaj post" name="submit"/></th></tr>
        </table>
    </form>


<script>

	CKEDITOR.replace( 'textarea-id',
	{
		height: '400px',
	} );
	
</script>
<?php } ?>
