<?php 
session_start();

if($_SESSION['accessLevel'] == 'admin') { ?>

<font class="page-title">Instrukcja obsługi serwisu</font>

<p>Poniższa instrukcja zawiera opis czynności oraz większości opcji dostęponych na stronie dla konta Administratora</p>

<p style="font-weight: bold">Dodawanie graczy</p>
<ul>
    <li>Aby dodać gracza najpierw trzeba wejść w zakładkę <font class="bold">"DODAJ GRACZA"</font>.</li>
    <li>Następnie należy wypełnić formularz danymi osobowymi uczestnika.</li>
    <li>Z konta admina można dodawać tylko uczestników rozgrywek</li>
    <li>Aby dodać konto administracyjne rozgrywek należy zalogować się na konto głównego <font class="bold">ADMINISTRATORA</font> (które zarządza całym serwisem) i z tego konta można założyć konta administratorów rozgrywek.</li>
    <li>Aby gracz mógł brac czynny udział w rozgrywekach <u>musi</u> w zakładce <font class="bold">"MOJE KONTO"</font> wyrazić zgodę na przetwarzanie danych osobowych na potrzeby serwisu.</li>
</ul>

<p style="font-weight: bold">Dodawanie i edycja postów</p>
<ul>
    <li>Dodawanie postów</li>
    <ul>
        <li>Aby dodać post należy wejść w zakładkę <font class="bold">"DODAJ POST"</font></li>
        <li>Należy wpisać tytuł postu w pierwszym polu tekstowym.</li>
        <li>Treść postu należy wpisać w drugim polu tekstowym. Podczas kreowania treści postu można użyć narzędzi do edycji tekstu.</li>
        <li>Aby zatwierdzić post należt kliknąć przycisk <font class="bold">"DODAJ POST"</font>, który znajduje się poniżej pól ekstowych.</li>
    </ul>
    <li>Edycja i usuwanie postów</li>
    <ul>
        <li>Aby edytować post należy kliknąć przycik <font class="bold"><i class="icon-pencil">EDYTUJ POST</i></font></li>
        <li>Następnie należ postęować tak samo jak przy dodawaniu postu.</li>
        <li>Na końcu należy kliknąć przycisk <font class="bold">"ZATWIERDŹ POST"</font>.</li>
        <li>Aby usunąć post, należy klinąć przysk <font class="bold"><i class="icon-cancel">USUŃ POST</i></font>.</li>
    </ul>
</ul>

<p style="font-weight: bold">Obsługa listy użytkowników</p>
<ul>
    <li>Użytkownicy sortowani są alfabetycznie roznąco według Nick'ów.</li>
    <li>Zmiana adresu E-m@il następuje poprzez kliknięcie na adres przy nazwisku danego gracza.</li>
    <li>Aby wysłać wiadomość E-m@il należy kliknąć na kopertę <i class="icon-mail"></i>obok adresu, następnie zostaniemy przekierowani do klienta poczty na naszym komputerze.</li>
        <ul>
            <li>Wiadomość można wysłac również poprzez skopiowanie adresu i wklejenie go w miejscu odbiorcy na naszym koncie pocztowym.</li>
        </ul>
    <li>Aby zmienić numer telefonu należy kliknąć na numer telefonu wybranego gracza i wpisać w okienu które się pojawi nowy nowyn numer, a następnie kliknąć "OK".</li>
    <li>Zmiana hasła następuje poprzez kliknięcie na kluczyk <i class="icon-key"></i> w polu edycja przy nazwisku danego gracza.</li>
    <li>Usunięcie konta gracza następuje poprzez kliknięcie na krzyżyk <i class="icon-cancel"></i> w polu edycji przy nazwisku danego gracza.</li>
    <li>Zawieszenie gracza w rozgrywkach nastepuje poprzez kliknięce na kłódkę <i class="icon-lock"></i> w polu edycji przy nazwisku danego gracza.</li>
</ul>


<p style="font-weight: bold">Mecze do potwierdzenia</p>
<ul>
    <li>W tej zakładce będą się pojawiać mecze, których wyniki wpisane przez obydwu graczy nie są zgodne.</li>
    <li>Przy zakładce pojawia się liczba informująca o ilości oczekujacych meczy do potwierdzenia.</li>
    <li>Należy zweryfikować wynik i wpisać odpowiedni, a następnie zatwierdzic klikająć ptaszka <i class="icon-ok"></i>.</li>
</ul>

<p style="font-weight: bold">Aktywne mecze</p>
<ul>
    <li>W tej zakładce pojawiać się będą mecze które jeszcze trwają, a zawodnicy nie wpisali jeszcze wyników.</li>
    <li>Jeżeli jest prośba ze strony graczy, admin może wpisać wynik, a następnie zatwierdzić go klikając w ptaszka <i class="icon-ok"></i>.</li>
    <li>Jeżeli klikniem w krzyżyk <i class="icon-cancel"></i> usuniemy aktywny mecz.</li>
</ul>

<p style="font-weight: bold">Panel admina</p>
<ul>
    <li>Każdy admin znajdzie tutaj przycisk do manualnego wywołania aktualizacji oczekujących meczy. Ponieważ skrypt dodający mecze i punkty wykonuje się cyklicznie co określony czas, możemy to przyspieszyć.</li>
</ul>

<p style="font-weight: bold">Panel admina (Tylko główny administrator)</p>
<ul>
    <li>Znajduję się tutaj opcja do resetowania bazy dancyh.</li>
    <ul>
        <li>Można usunąć same mecze.</li>
        <li>Jednocześnie mecze i użytkowników (graczy).</li>
        <li>Oraz mecze, użytkowników (graczy) oraz innych administratorów <font class="bold">(NIE DOTYCZY KONTA "ADMINISTRATOR")</font>.</li>
    </ul>
    <li>Znajduję się tutaj również tabelka do ustalania parametrów rozgrywek, takich jak:</li>
    <ul>
        <li>Możliwość tworzenia i wpisywania przez administratorów wyników meczy.</li>
        <li>Włączyć tryb debugowania na stronie.</li>
        <li>Możliwość wyłącznia logowania się do serwisu dla zwykłych użytkowników.</li>
        <li>Maksymalna możliwa ilość aktywnych meczy.</li>
        <li>Maksymalna liczba meczy między tymi samymi graczami.</li>
        <li>Maksymalny mozliwy wynik do wpisnia jako wynik macze.</li>
        <li>Punkt za samo rozegranie meczu.</li>
        <li>Dodatkowe punty za wygrany mecz.</li>
        <li>Oraz można zmienić teksty widoczne na stronie głównej przez i po zalogowaniu się oraz motto.</li>
    </ul>
</ul>

<?php } ?>