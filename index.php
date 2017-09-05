<?php
header("Pragma: no-cache");
//header("Cache-Control: max-age=2592000");

include "SessionManager.php";
SessionManager::sessionStart();
require_once("db_config.php");

if($CONFIG['disableLogin'] == true && $_SESSION['accessLevel'] == "user")
{
	$_SESSION = array();
}

if( isset($_SESSION['userID']) )
{
	$blocked = $GLOBALS['db_conn']->query("SELECT blocked FROM users WHERE id=$_SESSION[userID]")->fetch_assoc()['blocked'];
	if( $blocked )
	{
		$_SESSION = array();
	}
}

$_SESSION['rootPath'] = realpath("./");

//print_r($_SESSION);
?>


<!DOCTYPE html>
<html lang="pl">
    <head>

        <!--
                1. Autorami strony są Sebastian Juzeluk oraz Damian Ciftci.
                2. Cała strona (szablon strony, cała struktura jak i kod wykonywany po stronie serwera) chroniona jest prawem autorskim
                w rozumieniu Kodeksu Karnego Art.115. i przysługują jej autorom (patrz punkt 1.).
                3. Osoby naruszjące prawa autoskie mogą zostać pociągnięte do odpowiedzialności karnej za złamanie praw autorskich przysługujących ich twórcom w rozumieniu Kodeksu Karnego Art.115
                4. Strona zostaje przekazana dla Zespołu Szkół Energetycznych ul.Mikołaja Reja 25, 80-870 Gdańsk, tylko na potrzeby rozgrywek.
                5. Autorzy zastrzegają sobie prawo do modyfikowania i kopiowania serwisu.
                6. Powyższe postanowienia wchodzą w życie z dniem ogłoszenia stony na domenie lub sub-domenie szkolnej, albo na domenie założonej przez szkołę, nie bedącej spójną częścią strony szkoły.
        -->


        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Sebastian Juzeluk, Damian Ciftci" />
        <meta name="copyright" content="Damian Ciftci, Sebastian Juzeluk" />
        <meta name="description" content="Serwis internetowy tenisa stołowego w Zespole Szkół Energetycznych w Gdańsku. PING PONG, TENIS STOLOWY, ZABAWA, FAIR PLAY" />
        <meta name="keywords" content="tenis, stolowy, ping pong, tenis stolowy, Zespol, Szkol, Energetycznych, ZSE, Gdansk, Technikum nr 13, Techikum, numer, 13, Sebastian, Juzeluk, Damian, Ciftci, Sebastian Juzeluk, Damian Ciftci, stol, paletka, pileczka, rakietka" />
        <title>Tenis Stołowy w ZSE</title>
        <link rel="shortcut icon" href="img/favicon.ico" />

        <!-- Background pattern from subtlepatterns.com -->
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/admin.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/fontello.css" />
        <!--<link rel="stylesheet" type="text/css" href="css/dialogbox.css" />-->
        <!--<link rel="stylesheet" type="text/css" href="css/wow-alert.css" />-->
		<link rel="stylesheet" type="text/css" href="css/jquery.prompt.css"/>
		<!--<link rel="stylesheet" type="text/css" href="css/animate.css"/>-->
		<link rel="stylesheet" type="text/css" href="css/alertify.core.css"/>
		<link rel="stylesheet" type="text/css" href="css/alertify.default.css"/>
		<link rel="stylesheet" type="text/css" href="css/introjs.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>


        <!-- FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700|Oswald:400,700&subset=latin-ext" rel="stylesheet">

        <!-- SCRIPT -->
		<script src="js/jquery-3.1.0.min.js"></script>
		<script src="js/timer.js"></script>
		<script src="js/portamento.js"></script>
		<script src="js/wow-alert.js"></script>
		<!--<script src="js/animate-scroll.js"></script>-->
		<script src="js/skrypty.js"></script>
		<!--<script src="js/filter.js"></script>-->
		<!--<script src="js/jquery.prompt.js"></script>-->
		<!--<script src="js/jquery.viewportchecker.min.js"></script>-->
		<script src="js/alertify.min.js"></script>
        <script src="js/intro.js"></script>
        <script src="js/jquery-ui.min.js"></script>
		<script src="js/editpages.js"></script>
		<?php if($_SESSION['accessLevel'] == "admin") { ?>
		<script src="js/ckeditor/ckeditor.js"></script>

		<?php } ?>
		<script>
		function checkLastLogin()
		{
		  alertify.alert("Czy zezwalasz na przetwarzanie swoich danych na potrzeby tego serwisu\noraz strony zse.gda.pl\n", function(e)
		  {
		  	if(e)
		  	{
		  		$.post("php/updateLastLogin.php", { }, function(result)
		  		{ });
		  	}
		  	else
				{
					location.reload();
		  	}
		  });
		}
		</script>

        <?php if($_SESSION['accessLevel'] == "user") { ?>
		<script src="js/core.js"></script>
    <?php } ?>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

    <!--<script src="http://skryptcookies.pl/cookie.js"></script>

    <script src="http://cookiealert.sruu.pl/CookieAlert-latest.min.js"></script>
    <script>CookieAlert.init();</script>-->
    <script type="text/javascript" src="js/cookies.js"></script>

<!--<script src="js/scripts.js" type="text/javascript"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>



        <script>


		$(function() {
			$('.fade').fadeIn(0)
			$('#content').load('pages/start.php');

			var selector = 'nav ul li a';
			var selector2= '#top-bar #small-menu .link-top-bar a';
			var selector3= '#footer-info #footer-info-content .footer-right-section ul.menu_ul li a';
			$(selector).click(function(e) {
				if ($(this).hasClass('load')) {
					e.preventDefault();
					$(selector).removeClass('active');
					$(selector2).removeClass('active');
					$(selector3).removeClass('active');
					$(this).addClass('active');

					var target = 'pages/' + $(this).attr('class').split(' ')[1] + '.php';
					$('#content').load(target).hide().fadeIn(0);
				}
			});
		});

		$(function() {

			var selector = '#top-bar #small-menu .link-top-bar a';
			var selector2 = 'nav ul li a';
			var selector3 = '#footer-info #footer-info-content .footer-right-section ul.menu_ul li a';
			$(selector).click(function(e) {
				if ($(this).hasClass('load')) {
					e.preventDefault();
					$(selector).removeClass('active');
					$(selector2).removeClass('active');
					$(selector3).removeClass('active');
					$(this).addClass('active');

					var target = 'pages/' + $(this).attr('class').split(' ')[1] + '.php';
					$('#content').load(target).hide().fadeIn(0);
				}
			});
		});

        $(function() {

            var selector = '#footer-info #footer-info-content .footer-right-section ul.menu_ul li a';
            var selector2 = 'nav ul li a';
            var selector3 = '#top-bar #small-menu .link-top-bar a';
            $(selector).click(function(e) {
                if ($(this).hasClass('load')) {
                    e.preventDefault();
                    $(selector).removeClass('active');
                    $(selector2).removeClass('active');
                    $(selector3).removeClass('active');
                    $(this).addClass('active');

                    var target = 'pages/' + $(this).attr('class').split(' ')[1] + '.php';
                    $('#content').load(target).hide().fadeIn(0);
                }
            });
        });

		$.post( "php/conflictMatches.php", { "get": "1" }, function( result ) {

			$('.conflictMatches').html(result);

		});

		<?php
		if($_SESSION['accessLevel'] == "admin") { ?>
			window.setInterval(function(){


				$.post( "php/conflictMatches.php", { "get": "1" }, function( result ) {

					$('.conflictMatches').html(result);

				});

			}, 15000);
		<?php } ?>



		window.setInterval(function() {

		location.reload();
		}, 50 * 60 * 1000);

		alertify.set({ buttonReverse: true });
		alertify.defaults = { closable:true, closableByDimmer:true };
		alertify.set({ labels: {
		    ok     : "OK",
		    cancel : "ANULUJ"
		} });

        $(document).ready(function(){

	//Check to see if the window is top if not then display button
        $(window).scroll(function(){
                if ($(this).scrollTop() > 100) {
                    $('.scrollToTop').fadeIn();
                } else {
                    $('.scrollToTop').fadeOut();
                }
            });

            //Click event to scroll to top
            $('.scrollToTop').click(function(){
                $('html, body').animate({scrollTop : 0},800);
                return false;
            });

        });


        </script>


    </head>
    <body onload="timer(), background()">
	   <div id="backgrounddimming">
           <div id="changepwdadmin">
               <?php require_once("pages/editpost.php") ?>
           </div>
        </div>

        <a href="#" class="scrollToTop"><i class="icon-up-open"></i></a>

        <div id="container">
            <!-- TOP BAR start-->
            <section id="top-bar">
                <section id="small-menu">
                    <font class="link-top-bar"><a class="load about">O autorach</a></font>
                    <?php if($_SESSION['accessLevel'] == 'admin' || $_SESSION['accessLevel'] == 'user') { ?>
                        <font class="link-top-bar"><a class="tour" href="javascript:void(0);" onclick="javascript:introJs().setOption('showProgress', true).start();">Wycieczka po stronie</a></font>
                    <?php } ?>
                    <?php if($_SESSION['accessLevel'] == 'admin') { ?>
                        <font class="link-top-bar"><a class="load instruction">Instrukacja</a></font>
                    <?php }?>
                    <font class="link-top-bar" style="color: #2ab3d2; cursor: default">Wersja BETA 1.9.9.3</font>

                    <font id="clock"></font>
                </section>
            </section>
            <!-- TOP BAR end -->

            <!--HEADER start-->
            <header>
                <section id="logo" data-step="1" data-intro="Witaj w serwisie sportowym poświęconym tenisowi stołowemu.">
                    <section id="logo-img"><a id="link-logo-img"><img src="img/logotenis-site.png" alt="TENIS STOŁOWY ZESPÓŁ SZKÓŁ ENERGETYCZNYCH W GDAŃSKU" /></a></section>
                    <section id="logo-slogan">Ping Pong na każdej przerwie</section>
                </section>
                <!-- LOGIN start -->
                <aside data-step="2" data-intro="Tutaj znajdziesz opcje swojego konta. Między innymi zmiana hasła.">
                    <section id="login">
                        <h6 style="display: none;">Panel logowania - Profil gracza</h6>
					<?php require_once('pages/loginpanel.php'); ?>
                    </section>
				</aside>
                <!-- LOGIN end -->
                <div style="clear: both;"></div>
                <!-- NAV start -->
                <nav class="nav" data-step="3" data-intro="Słuzy do poruszanie się pomiędzy zakładkami serwisu.">
                    <?php require_once('pages/menu.php'); ?>
                </nav>
                <!-- NAV end -->
            </header>
            <!--HEADER end-->

            <!-- CONTENT start -->
            <content>
                <section id="content"></section>
                <section id="top5"></section>
                <section id="last-score"></section>
            </content>
            <!-- CONTENT end -->

            <!-- FOOTER start-->
            <footer>
                <section id="footer-info">
                    <section id="footer-info-content">

                        <section class="footer-left-section footer-section" data-step="5" data-intro="TOP 5 najlepszych graczy.">
                            <h6 style="display: none;">Top 5 najlepszych zawodników</h6>
                            <font class="title-footer">Top 5</font>
                            <table>
                                <?php require_once("pages/topfive.php") ?>
                            </table>
                        </section>

                        <section class="footer-center-section footer-section" data-step="6" data-intro="Wyniki z ostatnio rezegranych meczy.">
                            <h6 style="display: none;">Ostatnie wyniki</h6>
                            <font class="title-footer">Ostatnie wyniki</font>
                            <table id="table-lastresult">
                                <?php require_once("pages/latestresult.php") ?>
                            </table>
                        </section>

                        <section class="footer-right-section footer-section">
                            <h6 style="display: none;">Bottom-menu</h6>
                            <?php require("pages/menu.php"); ?>
                            <?php require_once("pages/nav/privacy_nav.php"); ?>
                            <?php require('pages/nav/admin_nav.php'); ?>
                        </section>

                    </section>
                </section>
                <div style="clear:both"></div>
                <section id="footer-contact" class="info-about-us"><font><a class="mail-footer" href="mailto:damianciftci@gmail.com">Damian Ciftci</a> & <a class="mail-footer" href="mailto:s.juzeluk@gmail.com">Sebastian Juzeluk</a> &copy; Wszelkie prawa zastrzeżone</font></section>

                <section id="footer">

                    <section id="footer-content">Tenis stołowy ZSE - 2016<?php if(date("Y")>'2016'){ echo " - "; echo date("Y");} ?> <font id="info-text-footer" onclick="showinfo()"><i class="icon-info" data-step="7" data-intro="Kliknij aby zobaczyć."></i></font></section>

                </section>
            </footer>
            <!-- FOOTER end -->
        </div>
				<?php
				if(isset($_SESSION['userID']) && $_SESSION['lastlogin'] == 0)
				{ ?>
				<script>

				checkLastLogin();

				</script>
			<?php } else if(isset($_SESSION['userID']))
				{
					// DO NAPRAWY
					// $_SESSION['lastlogin'] = time();
					// $conn = $GLOBALS['db_conn'];
					// $updateLastLogin = "UPDATE users SET lastlogin=$_SESSION[lastlogin] WHERE id=$_SESSION[userID]";
					// $conn->query($updateLastLogin);
				} ?>
        <script>


          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-85702397-1', 'auto');
          ga('send', 'pageview');

        </script>
    </body>
</html>
