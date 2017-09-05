/*Load pages to content*/


// $(function() {      

    // var selector = '#top-bar #small-menu .link-top-bar a';
    // var selector2 = 'nav ul li a';
    // var selector3 = '#footer-info #footer-info-content .footer-right-section ul.menu_ul li a';
    // $(selector).click(function(e) {
        // if ($(this).hasClass('load')) {   
            // e.preventDefault();
            // $(selector).removeClass('active');
            // $(selector2).removeClass('active');
            // $(selector3).removeClass('active');
            // $(this).addClass('active');

            // var target = 'pages/' + $(this).attr('class').split(' ')[1] + '.php';
            // $('#content').load(target).hide().fadeIn(0);
        // }
    // }); 
// });



function LoadFile(file, where) {
    $(where).load('../pages/' + file + '.php').hide().fadeIn(0);
}

function background() {
    var currentDate = new Date();
    var day = currentDate.getDate();
    
    if(day % 2 == 0)
        {
            document.body.style.backgroundImage = "url(../img/green_cup.png)";
        }
    else
        {
            document.body.style.backgroundImage = "url(../img/back-tenis-2.jpg)";
        }
}

function validateForm(formId)
{
    var inputs, index;
    var form=document.getElementById(formId);
    inputs = form.getElementsByTagName('input');
    for (index = 0; index < inputs.length; ++index) {
        // deal with inputs[index] element.
        if (inputs[index].value==null || inputs[index].value=="")
        {
            return false;
        }
    }
}


/*Change title page*/
function changetitle(changed) {
    $(document).attr("title", changed);
}

/*Extra footer*/
function showinfo() {
    $('#footer-contact').slideToggle(200);
}

function showmenu() {
    $('.menu_ul').slideToggle(1000);
}

$(document).ready(function(){
 
		$('*[data-animate]').addClass('hide').each(function(){
      $(this).viewportChecker({
        classToAdd: 'show animated ' + $(this).data('animate'),
        classToRemove: 'hide',
        offset: '30%'
      });
    });
 
	});