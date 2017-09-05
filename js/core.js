
function challangeplayer(id, name, surname)
{
    alertify.confirm("Czy napewno chcesz wyzwać <br><b>" + name + " " + surname + "</b>", function(e) {
        if(e) {

            $.post("php/handlechallange.php", {"type": "challange", "opponent_id": id}, function(result) {

                    if(result == 'success')
                        alertify.success("Przeciwnik zostal wyzwany.");
                    else
                        alertify.error(result);

					console.log(result);
                });

        }
    });
}


function cancelChallange(opponent_id)
{

	$.post("php/handlechallange.php", {"type": "cancelchallange", "opponent_id": opponent_id}, function(result) {


	if(result == "success")
	{
		alertify.success("Wyzwanie zostalo anulowane");
	}
	else
	{
		alertify.error(result);
	}

	console.log(result);
	$('#content').load('pages/findoponent.php');
});
}

function denyChallange(opponent_id)
{

	$.post("php/handlechallange.php", {"type": "denychallange", "opponent_id": opponent_id}, function(result) {



	if(result == "success")
	{
		alertify.success("Wyzwanie zostalo odrzucone");
	}
	else
	{
		alertify.error(result);
	}

	console.log(result);
	$('#content').load('pages/findoponent.php');
});
}

function acceptChallange(opponent_id)
{

	$.post("php/handlechallange.php", {"type": "acceptchallange", "opponent_id": opponent_id}, function(result) {


	if(result == "success")
	{
		alertify.success("Wyzwanie zostalo przyjęte");
		//$('#content').load('pages/findoponent.php');
	}
	else
	{
		alertify.error(result);
	}

	console.log(result);
	$('#content').load('pages/findoponent.php');
});
}

function sendResult(id, opponent_id, num)
{

	var p1_result = $('#wynik1-' + id).val();
	var p2_result = $('#wynik2-' + id).val();


	$.post("php/sendresult.php", {"opponentId": opponent_id, "num": num, "p1_result": p1_result, "p2_result": p2_result}, function(result) {

		if(result == "success")
		{
			alertify.success("Twój wynik został wprowadzony, oczekiwanie na przeciwnika lub na zatwierdzenie meczu przez system");
			$('#content').load('pages/findoponent.php');
		}
		else
		{
			alertify.error(result);
			console.log(result);
		}


	});

	$('#content').load('pages/findoponent.php');
}

function displayPlayerInfo(id)
{
	$.post("php/getplayerinfo.php", {"id": id}, function(result) {

		if(result == "error" || result == "") {

			alertify.alert("Błędne ID lub błąd połączenia z bazą.");
		}
		if(result == "permissions") {

			alertify.alert("Brak uprawnień lub błędne dane wejściowe");
		}
		else {

			alertify.alert(result).set({'closableByDimmer': true});
		}

		console.log(result);
	});

}

function testcore(id)
{
	alertify.success(id);
}
