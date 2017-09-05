<?php session_start();

if($_SESSION['userID'] == 1 && strtolower($_SESSION['user']) == "administrator") {
	$isMainAdmin = true;
}
else {
	$isMainAdmin = false;
}

if($_SESSION['accessLevel'] == 'admin') { 
?>
	
<form id="adduser-form" onsubmit="return validateForm('adduser-form');">
	<table id="adduser-table">
		<tr>
			<th></th>
			<th class="login-title onethird">Stwórz konto</th>
			<th class="onethird"></th>
		</tr>
		
		<tr>
			<th class="label"><label for="username">Nazwa użytkownika</label></th>
			<th><input class="login-input username adduser" type="text" name="username" placeholder="" autocomplete="off" autofocus required/></th>
			<th></th>
		</tr>
		
		<tr>
			<th class="label"><label for="name">Imię</label></th>
			<th><input class="login-input adduser" type="text" name="name" placeholder="" autocomplete="off" required /></th>
			<th></th>
		</tr>
		
		<tr>
			<th class="label"><label for="surname">Nazwisko</label></th>
			<th><input class="login-input surname adduser" type="text" name="surname" placeholder="" autocomplete="off" required/></th>
			<th></th>
		</tr>
		
		<tr>
			<th class="label"><label for="password">Hasło</label></th>
			<th><input class="login-input password adduser" type="password" name="password" placeholder="" autocomplete="off" required/></th>
			<th></th>
		</tr>
		
		<tr>
			<th class="label"><label for="password">Powtórz hasło</label></th>
			<th><input class="login-input password adduser" type="password" name="password-repeat" placeholder="" autocomplete="off" required/></th>
			<th></th>
		</tr>
		
		<tr>
			<th class="label"><label for="email">Adres e-mail</label></th>
			<th><input class="login-input email adduser" type="email" name="email" placeholder="" autocomplete="off" required/></th>
			<th></th>
		</tr>

		<tr>
			<th class="label"><label for="number">Numer telefonu</label></th>
			<th><input class="login-input number adduser" type="text" minlength="9" maxlength="9" name="number" placeholder="" autocomplete="off" onkeypress="return isNumberKey(event)" required/></th>
			<th></th>
		</tr>
		 
		<tr>
			<th class="label"><label for="access">Typ konta</label></th>
			<th>
				<select name="access" class='access adduser'>
					<optgroup label="Rodzaj konta"></optgroup>
					<option value="user">Gracz</option>
					<?php if($isMainAdmin) { ?>
					<option value="admin">Administrator</option>
					<?php } ?>
					
					</select>
			</th>
			<th></th>
		</tr>
		
		<tr>
			<th></th>
			<th><input type="checkbox" name="accept-rules" checked="checked"/>Akceptuje regulamin serwisu.</th>
			<th></th>
		</tr>
		
		<tr>
			<th></th>
			<th><input class="login-input submit adduser" type="submit" value="Stwórz" name="submit" onclick=""/></th>
			<th></th>
		</tr>
	</table>
</form>

<script>
    
$("input").change(function() {
	
    var name = $(this).attr("name");
    var content = $(this).val();
    var target = ".error.e_" + name;

    var span = " <span class='error e_" + name + "'></span>";
    var where = $(this).closest("th").find("label");
    console.log($(span));
    
    if (!$(this).closest("th").find("span").length)
        $(span).insertAfter(where);
    
    var ok = true;
    
    // DO ZROBIENIA
    
    switch (name)
    {
        case "username":
            $(target).text((content.length < 3) ? "Minimum 3 znaki." : "");
            break;
            
        case "name":
            var reg = /^[A-Za-zęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+$/;
            $(target).text((content.length < 3) ? "Minimum 3 litery." : (!reg.test(content)) ? "Tylko litery!" : "");
            break;
            
        case "password":
            $(target).text((content.length < 6) ? "Minimum 6 znaków." : "");
            break;
            
        case "email":
            var reg = /^[A-Za-z0-9._-]+[@]+[a-z0-9._-]+[.]+[a-z]+$/;
            $(target).text((content.length < 12) ? "Minimum 12 znaków." : (!reg.test(content)) ? "Email jest nieprawidłowy!" : "");
            break;
            
        case "number":
            $(target).text((content.length < 9) ? "Numer nie ma 9 cyfr." : "");
            break;
            
        case "surname":
            var reg = /^[A-Za-zęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+$/;
            $(target).text((content.length < 3) ? "Minimum 3 litery." : (!reg.test(content)) ? "Tylko litery!" : "");
            break;
    }
});
    
$('.submit').click(function(event) {
		event.preventDefault();
		var username = $("input[name='username'].adduser").val();
		var name = $("input[name='name'].adduser").val();
		var surname = $("input[name='surname'].adduser").val();
		var password = $("input[name='password'].adduser").val();
		var email = $("input[name='email'].adduser").val();
		var number = $("input[name='number'].adduser").val();
		var access = $("select[name='access'].adduser").val();
    
        var email_reg = /^[A-Za-z0-9._-]+[@]+[a-z0-9._-]+[.]+[a-z]+$/;
        var number_reg = /^[0-9]{9}$/;
    
		//alert(access);
        
        
    
    
		if(username.trim() == '' ||
			name.trim() == '' ||
			surname.trim() == '' ||
			password.trim() == '' ||
			email.trim() == '' ||
			number.trim() == '')
		{
			//alertify.alert("Pola muszą zostać wypełnione");
			alertify.error("Wszystkie pola muszą zostać wypełnione");
			return;
		}
		
		//EMAIL ALERT GDY JEST BŁĘDNY
		if( !email_reg.test(email) ) {
			
			alertify.error("Nieprawidłowy e-mail");
			return;
		}
		
		
		//GDY NUMER JEST BŁEDNY
		if( !number_reg.test(number) ) {
			
			alertify.error("Numer telefonu musi mieć 9 cyfr!");
			return;
		}
			
    
		$.post("php/createuser.php", {"username": username, "name": name, "surname": surname, "password": password, "email": email, "number": number, "access": access}, function(result) {
            
			
			if(result == "success")
			{
				alertify.success("Utworzono użytkownika");
				$("input[name='username'].adduser").val('');
				$("input[name='name'].adduser").val('');
				$("input[name='surname'].adduser").val('');
				$("input[name='password'].adduser").val('');
				$("input[name='email'].adduser").val('');
				$("input[name='number'].adduser").val('');
				$("select[name='access'].adduser").val('');
			}
			else
			{
				alertify.error(result);
			}
			
			console.log(result);
		});
	});
</script>
    
<?php } ?>