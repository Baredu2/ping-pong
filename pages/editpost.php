<?php
echo $_GET['id'];

?>

<section class="account-content">
    <i class="icon-cancel exit-changepassword" style="float: right; font-size: 25px; margin-right: 15px;"></i>
        <form id="changepwdadmin-form" style="margin-top: 25px;" method="post" onsubmit="return validateForm('changepwdadmin-form');">
            <table id="adduser-table">
                <tr><th class="login-title">Zmiana hasła</th></tr>
                <tr><th id="pass-info"></th></tr>
                <tr><th><input class="change-password" type="password" name="newpassword" placeholder="Podaj nowe hasło" autocomplete="off" autofocus/></th></tr>
                <input type="hidden" id="user_id" value="" name="id" />
                <tr><th><input class="change-submit" type="submit" value="Zmień hasło" name="submit"/></th></tr>
            </table>
        </form>
</section>

<script>


	$('.change-submit').click(function(event) {
		event.preventDefault();
		var password = $('.change-password').val();
		var id = $('#user_id').val();
		//alert(password);
	
		$.post("php/newpassword-admin.php", {"id": id, "newpassword": password}, function(result) {
			visibilitynone("backgrounddimming", "changepwdadmin");
			$('.change-password').val('');
            console.log(result);
		});
	});
    
    $('.icon-cancel.exit-changepassword').click(function(event) {
	
        visibilitynone("backgrounddimming", "changepwdadmin");
        $('.change-password').val('');
	});
	
	
</script>
