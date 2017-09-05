

function editpage() 
{
	var id = $('.page').attr('id');
	
	CKEDITOR.replace( id,
	{
		height: '700px',
	} );
	document.getElementById("editpage").setAttribute("onclick","updatepage()");
	$("#editpage").html("Zapisz strone");
}

function updatepage()
{
	var page = $('.page').attr('id');
	var content = CKEDITOR.instances[page].getData();
	
	
	$.post("php/updatePage.php", {"page": page, "html": content }, function(result) {
		
		if(result == "success")
		{ 
			alertify.success("Strona została zaktualizowana");
			$('#content').load('pages/' + page + '.php');
		}
		
		else
		{
			alertify.error("Nieznany błąd, info w konsoli");
		}
		
		console.log(result);
		
		
	});
}