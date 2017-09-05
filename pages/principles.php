<?php

session_start();
require_once("../db_config.php");

?>

<font class="page-title">Zasady</font>
<ul id="list-participles">
    <li><i class='icon-right-open-outline'></i>Gramy na przerwach lekcyjnych.
    <li><i class='icon-right-open-outline'></i>Mecz składa się tylko z jednego seta, do uzyskania <?php print($CONFIG['maxPointsInGame']) ?> punktów (czyli np. <?php print($CONFIG['maxPointsInGame']) ?>:<?php print($CONFIG['minusOne']) ?>) <font class="bold">UWAGA:</font> Nie gramy do uzyskania dwupunktowej przewagi.</li>
    <li><i class='icon-right-open-outline'></i>Gramy z jak największą ilością zawodników.</li>
    <li><i class='icon-right-open-outline'></i>Z każdym zawodnikiem możemy zagrać maksymalnie punktowanych meczy: <?php print($CONFIG['maxNumberOfMatchesBetweenPlayers']) ?>.</li>
    <li><i class='icon-right-open-outline'></i>Po każdym meczu zawodnicy samodzielnie w formularzu pomeczowym wpisują wynik meczu.</li>
    <li><i class='icon-right-open-outline'></i>W czasie zawodów obowiązuje sportowe obuwie zamienne.</li>
    <li><i class='icon-right-open-outline'></i>Sprzęt sportowy (rakietki i piłeczki) zawodnicy zabezpieczają we własnym zakresie.</li>
    <li><i class='icon-right-open-outline'></i>Nadrzędna zasada, której wymagamy od zawodników grających w rozgrywkach <font class="bold">Ping Pong na każdej przerwie</font>, to zasada <font class="underline">Fair Play</font>.</li>
</ul>
