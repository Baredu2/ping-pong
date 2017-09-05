var r;
var e;

function odbierzDane()
{
  if (r.readyState == 4) {
    if (r.status == 200 || r.status == 304) {
      e.innerHTML = r.responseText;            
    }
  }
}

function wymienTresc(adresurl, htmlid)
{
  if (r = getXMLHttpRequest()) {
    e = document.getElementById(htmlid);
    r.open('GET', adresurl);
    r.onreadystatechange = odbierzDane;
    r.send(null);    
  }
}