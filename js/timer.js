function timer()
	{
		var dzisiaj = new Date();
		var dzien = dzisiaj.getDate();
		var miesiac = dzisiaj.getMonth()+1;		
		switch(miesiac)
		{
			case 1:
				miesiac="styczeń";
				break;
			case 2:
				miesiac="luty";
				break;
			case 3:
				miesiac="marzec";
				break;
			case 4:
				miesiac="kwiecień";
				break;
			case 5:
				miesiac="maj";
				break;
			case 6:
				miesiac="czerwiec";
				break;
			case 7:
				miesiac="lipiec";
				break;
			case 8:
				miesiac="sierpień";
				break;
			case 9:
				miesiac="wrzesień";
				break;
			case 10:
				miesiac="pażdziernik";
				break;
			case 11:
				miesiac="listopad";
				break;
			case 12:
				miesiac="grudzień";
				break;
		}
		
		var rok = dzisiaj.getFullYear();
		var godzina = dzisiaj.getHours();
		if (godzina<10) godzina = "0"+godzina;
		var minuta = dzisiaj.getMinutes();
		if (minuta<10) minuta = "0"+minuta;
		var sekunda = dzisiaj.getSeconds();
		if (sekunda<10) sekunda = "0"+sekunda;
		document.getElementById("clock").innerHTML = 
		godzina+" : "+minuta+" : "+sekunda+"&nbsp;&nbsp;&nbsp;&nbsp;  "+dzien+" "+miesiac+" "+rok;
        
        setTimeout("timer()",1000);
	}