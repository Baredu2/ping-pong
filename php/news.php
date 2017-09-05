<?php
ini_set("display_errors", 0);

session_start();

require_once($_SESSION['rootPath'] . "/db_config.php");
$conn = $GLOBALS['db_conn'];


$findNewsSql = "SELECT * FROM News ORDER BY date DESC, last_edit DESC";
$findNews = $conn->query($findNewsSql);

if($findNews->num_rows > 0)
{
	for($i = 0; $i < $findNews->num_rows; $i++)
	{
		$row = $findNews->fetch_assoc();

		$id = $row['id'];
		$title = $row['title'];
		$news = $row['news'];
		$date = $row['date'];
		/*$date = date( "d, Y", strtotime($date));*/


		$dzien = date("d", strtotime($date));
		$miesiac = date("n", strtotime($date));
		$rok = date("Y", strtotime($date));

		$miesiac_pl = array(1 => 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień');

		/*$date = dateV('l j f Y',strtotime('2009-09-02'));*/
		$author = $row['author'];

		//$news = nl2br($news);
		echo "<div class='post' id='id-$id' data-animate-scroll='{'alpha': '0', 'duration': '2', 'rotationY':'45', 'z':'-30'}'>";
		echo "<div class='title-post' id='$id'>$title</div>";
		echo "<div class='text-post' id='$id-id'>". html_entity_decode($news) ."</div>";
		if ($_SESSION['accessLevel'] == 'admin')
		{
			echo "<div class='del-post'><i class='icon-cancel delete'>Usuń post</i></div>";
			echo "<div class='edit-post'><i class='icon-pencil edit'>Edytuj post</i></div>";
//			echo "<div class='update-date-post'><i class='icon-arrows-cw update'>Aktualizuj datę</i></div>";
		}
		echo "<div class='author-post'><i class='icon-user-1'></i>$author</div>";
		echo "<div class='date-post'><i class='icon-clock'></i>";
		echo "$miesiac_pl[$miesiac] $dzien, $rok";
		echo "</div>";
		echo "<div style='clear:both'></div>";
		echo "</div>";
	}
}
else
	echo '<h1 style="text-align: center;" class="start-title">Brak postów do wyświetlenia</h1>';


$conn->close();

if($_SESSION['accessLevel'] == "admin") {
?>
<script>
function br2nl(str) {
    return str.replace(/<br\s*\/?>/mg,"");
}

$('.icon-cancel.delete').click(function() {
	var id = $(this).closest('.post').find('.title-post').attr('id');

	alertify.confirm("Czy napewno chcesz usunąć ten post", function(e) {
		if(e) {
			$.post("php/delnews.php", {"id": id});

			alertify.success("Post został usunięty");
			$('#content').load('pages/start.php');
		}
	});

	$('#content').load('pages/start.php');
});


$('.icon-pencil.edit').click(function() {
	var id = $(this).closest('.post').find('.title-post').attr('id');

	var title = $('.title-post#' + id).html();
	var content = $('.text-post#' + id + '-id').html();
	var areaid = "content-" + id;

	$('.post#id-' + id).html("<table id='addpost-table'><tr><th class='login-title'>Edytuj post</th></tr><tr><th><input class='login-input' id='title-"+ id +"' type='text' name='title' maxlength='50' placeholder='Tytuł postu (max 50 znaków)' value='" + title + "' autofocus/></th></tr><tr><th><textarea class='login-input' id='content-"+ id +"' type='text' name='message' placeholder='Treść postu'>"+ content +"</textarea></th></tr><tr><th><input class='login-input' onclick='updatePost("+ id +")' type='submit' value='Zatwierdź edycje' name='submit'/></th></tr></table>");
	$('<script>')
    .attr('type', 'text/javascript')
    .text("CKEDITOR.replace( '" + areaid + "', { height: '700px',} )")
    .appendTo('head');
});


//$('.icon-arrows-cw.update').click(function() {
//
//    var id = $(this).closest('.post').find('.title-post').attr('id');
//    var date = Date.now();
//
//    alertify.confirm("Czy napewno chcesz zaktualizować datę tego postu?", function(e) {
//        if(e) {
//            $.post("php/updateDatePost.php", {"id": id});
//
//            alertify.success("Data została zaaktualizowana");
//            $('#content').load("pages/start.php");
//        }
//    })
//});


function updatePost(id)
{

	var title = $('#title-' + id).val();
	var element_id = 'content-' + id;
	var content = CKEDITOR.instances[element_id].getData();


	$.post("php/updatePost.php", {"id": id, "title": title, "content": content}, function(result) {

		if(result == "success")
		{
			alertify.success("Post został zaktualizowany");
		}

		else if(result == "inputerror")
		{
			alertify.error("Błąd danych wejściowych");
		}

		else if(result == "databaseerror")
		{
			alertify.error("Błąd z połączniem z bazą danych");
		}
		else if(result == "queryerror")
		{
			alertify.error("Błąd z zapytaniem SQL");
		}

		else
		{
			alertify.error("Nieznany błąd, info w konsoli");
		}

		console.log(result);
		$('#content').load('pages/start.php');
	});

}

</script>

<?php } ?>
