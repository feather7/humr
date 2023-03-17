<?php 
require 'db_conn.php';
require '../logincheck.php';
$user = $_SESSION["user"];
$role = $_SESSION["role"];

//role vypsané níže nemůžou zapojovat
if($role == 1 || $role == 9) { $zapojovat = true; } else { $zapojovat = false; }
////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/png" href="/ppep3.png">
    <title>connlist</title>
    <link rel="stylesheet" href="css/style6.css">
</head>
<body>

<!--loading image-->
<div class='spinner-wrapper'>
<img src="img/Ellipsis.gif" width="80px">
</div>

<!--tlačítka výběr zobrazení-->
<div class="vrchni">
<form method="post">
    <div id="myDropdown" class="dropdown-content">
        <button id="strana_a" class="tlacitko"type="submit" value="A" name="strana_a">A</button>
        <button id="strana_b" class="tlacitko"type="submit" value="B" name="strana_b">B</button>
        <button id="strana_c" class="tlacitko"type="submit" value="C" name="strana_c">C</button>
        <button id="strana_u" class="tlacitko"type="submit" value="U" name="strana_u">U</button>
        <button id="strana_v" class="tlacitko"type="submit" value="V" name="strana_v">V</button>
        <button id="strana_w" class="tlacitko"type="submit" value="W" name="strana_w">W</button>
        <br>
        <button id="strana_an" class="tlacitko"type="submit" value="A - Zbývá" name="strana_an">A - Zbývá</button>
        <button id="strana_bn" class="tlacitko"type="submit" value="B - Zbývá" name="strana_bn">B - Zbývá</button>
        <button id="strana_cn" class="tlacitko"type="submit" value="C - Zbývá" name="strana_cn">C - Zbývá</button>
        <button id="strana_un" class="tlacitko"type="submit" value="U - Zbývá" name="strana_un">U - Zbývá</button>
        <button id="strana_vn" class="tlacitko"type="submit" value="V - Zbývá" name="strana_vn">V - Zbývá</button>
        <button id="strana_wn" class="tlacitko"type="submit" value="W - Zbývá" name="strana_wn">W - Zbývá</button>
        <br>
        <button id="bridge" class="tlacitko"type="submit" value="Bridge" name="bridge">Bridge</button>
        <button id="lanka" class="tlacitko" type="submit" value="Lanka" name="lanka">Lanka</button>
        <button id="kabely" class="tlacitko"type="submit" value="Kabely" name="kabely">Kabely</button>
        <button id="optiky" class="tlacitko"type="submit" value="Optiky" name="optiky">Optiky</button>
        <br>
        <button id="bridge_n" class="tlacitko"type="submit" value="Bridge - Zbývá" name="bridge_n">Bridge - Zbývá</button>
        <button id="lanka_n" class="tlacitko" type="submit" value="Lanka - Zbývá" name="lanka_n">Lanka - Zbývá</button>
        <button id="kabely_n" class="tlacitko"type="submit" value="Kabely - Zbývá" name="kabely_n">Kabely - Zbývá</button>
        <button id="optiky_n" class="tlacitko"type="submit" value="Optiky - Zbývá" name="optiky_n">Optiky - Zbývá</button>
        <br>
        <button id="ws0" class="tlacitko"type="submit" value="WS0" name="ws0">WS0</button>
        <button id="ws1" class="tlacitko"type="submit" value="WS1" name="ws1">WS1</button>
        <button id="ws2" class="tlacitko"type="submit" value="WS2" name="ws2">WS2</button>
        <button id="ws3" class="tlacitko"type="submit" value="WS3" name="ws3">WS3</button>
        <br>
        <button id="vsechno" class="tlacitko"type="submit" value="Všechno" name="vsechno">Všechno</button>
        <button id="zbyva" class="tlacitko"type="submit" value="Zbývá" name="zbyva">Zbývá</button>
        <button id="dvojdutinkyfrom" class="tlacitko"type="submit" value="Dvojdutinky From" name="dvojdutinkyfrom">Dvojdutinky <br> From</button>
        <button id="zmeny" class="tlacitko"type="submit" value="Změny" name="zmeny">Změny</button>
    </div> 
</form>
</div>

<!-- Navbar HTML -->
<div class='content-wrapper'>
    <div class='navmenu'>
        <div><a href='/' itemprop='url'><span itemprop='name'>Domů</span></a></div>
        <div><a href='seznam.php' itemprop='url'><span itemprop='name'>Seznam</span></a></div>
        <div><a id="logout" href='../logout.php' itemprop='url'><span itemprop='name'>Odhlásit</span></a></div>

        <div style="margin-left:20px"><a id="dropbtn" onclick="myFunction()" class="dropbtn">Vyber náhled</a></div>
        <div><a id="poslednizapojen" onclick="scrollNaVodic()" class="poslednivodic">pokračuj</a></div>
        
        <input style="margin-left:auto; order:1;width:100px;" onkeyup="filtr()" id="filtr" type='text' name="submit" placeholder='Filtr'/>

        <div id="info"><img src="img/info.png" width="35px">
            <div class="sipka">
                <div class="informace">
                    RT = vpravo/nahoře<br>
                    LB = vlevo/dole<br>
                    L = vlevo<br>
                    R = vpravo<br>
                    T = nahoře<br>
                    B = dole<br>
                </div>
            </div>
        </div>

        <form id='search-form' method='get'>
            <input id=placeholder name='search' placeholder='Jaký projekt?' maxlength="11" size='15' type='text'/>
            <input id='button-submit' type='submit' name="submit" value='Hledej'/>
        </form>
    </div>

    <div class ="top_menu_bar">

<script>
    /* When the user clicks on the button, 
    toggle between hiding and showing the dropdown content */
    function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
    const dropbtn = document.getElementById("dropbtn"); 
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
        }
    }
}
</script>

<script>
    let spinnerWrapper = document.querySelector('.spinner-wrapper');

    window.addEventListener('load', function () {
        // spinnerWrapper.style.display = 'none';
        spinnerWrapper.parentElement.removeChild(spinnerWrapper);
    });
</script>
<?php        
    if (isset($_GET["submit"])) {
        //seber zadanou hodnotu
        $napsano = $_GET["search"];
        //ověř zda DB má hodnoty
        $con = mysqli_connect("localhost","root", "","connlist");
        $query = "SELECT * FROM `$napsano`"; //to co jsi z db vybral dej pod $query
        $result = mysqli_query($con,$query); //pripojeni k db a hodnotu dej pod $result
        ?> <script>  //nastavit placeholder dle zadání
            var placeholdervalue = "<?php echo $napsano ?>";
            var placeholderjs = document.getElementsByName('search')[0].value=placeholdervalue;
        </script> <?php

        if(!$result){ //pokud tabulku nemáme tak ukonči a napiš:
            ?> <div class="empty2"> <?php
            die("CL $napsano nebyl ještě nahraný, nebo je chybně napsán.");
            ?> </div> <?php
        }

        //zjistit nejvyšší číslo revize
        $maxrev = $conn->query("SELECT rev FROM `$napsano` ORDER BY rev DESC limit 1"); 
        while($maxrev1 = $maxrev->fetch(PDO::FETCH_ASSOC)) { $cislorevize = (int)$maxrev1['rev']; }

        $hledanarev = 0;

        while($cislorevize >= pow(2, $hledanarev)){
            
            $hledanarev++;
        }
        echo "<a style=float:left>";
        echo "REV" . $hledanarev . ".  "; 
        echo "</a>";

        $lowrev = pow(2, $hledanarev-1);
        $highrev = pow(2, $hledanarev) - 1;

        $midrev = 1 + $highrev / 2;

        //pokud existuje BussWiring tak ho zobraz
        $bwnapsano = "{$napsano}bw";
        $connection = new mysqli("localhost", "root", "", "connlist");
        $checkbw = $connection->query("SELECT * FROM `$bwnapsano`");

        //pokud existuje BussWiring tak ho zobraz                
        if ( !empty($checkbw->num_rows) && $checkbw->num_rows > 0) //sem se dotázat zda v SQL jsou hodnoty, pokud jsou, zobraz tlačítko
        { ?> 
        <button id="bw" onclick="location.href='index.php?search=<?php echo $bwnapsano; ?>&submit=Potvrď' ;" style="background-color:yellow; color:black; cursor:pointer; float:right;">Přejít na BussWiring?</button>
        <?php }

        //zjisti zda operator potvrdil PRL    
        $napsanobezbw = substr($napsano, 0, 9);
        $prlkliksql = $conn3->query("SELECT * FROM prl where project LIKE '$napsanobezbw' and stamp_40 LIKE '%$user%'"); 
        $prlklik = $prlkliksql->rowCount();

        //vypiš hodnoty tabulky potom co zjistíš jaké byli chtěné!/////////////////////////////
        //hodnoty pokud nejsou definované    
        $zapojenobridge = ""; $zapojenocelkem= "";$celkembridge = "";
        
        switch (isset($_POST))
        {
            case isset($_POST['strana_a']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE fromloc LIKE '%.A.%'");
                // AND (checked = 1 AND rev < $lowrev OR rev >= $lowrev AND rev <= $highrev)
                ?><script> strana_a.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "A";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.A.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND fromloc LIKE '%.A.%'"); 
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE (fromloc LIKE '%.A.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_an']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (fromloc LIKE '%.A.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> strana_an.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "A - Zbývá";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.A.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_b']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE fromloc LIKE '%.B.%'");
                ?><script> strana_b.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "B";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.B.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))"); 
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND fromloc LIKE '%.B.%'");         
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE (fromloc LIKE '%.B.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_bn']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (fromloc LIKE '%.B.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> strana_bn.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "B - Zbývá";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.B.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_c']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE fromloc LIKE '%.C.%'");
                ?><script> strana_c.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "C";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.C.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND fromloc LIKE '%.C.%'"); 
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE (fromloc LIKE '%.C.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_cn']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (fromloc LIKE '%.C.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> strana_cn.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "C - Zbývá";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.C.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_u']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE fromloc LIKE '%.U.%'");
                ?><script> strana_u.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "U";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.U.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND fromloc LIKE '%.U.%'"); 
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE (fromloc LIKE '%.U.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_un']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (fromloc LIKE '%.U.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> strana_un.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "U - Zbývá";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.U.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_v']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE fromloc LIKE '%.V.%'");
                ?><script> strana_v.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "V";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.V.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND fromloc LIKE '%.V.%'"); 
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE (fromloc LIKE '%.V.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_vn']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (fromloc LIKE '%.V.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> strana_vn.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "V - Zbývá";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.V.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_w']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE fromloc LIKE '%.W.%'");
                ?><script> strana_w.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "W";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.W.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND fromloc LIKE '%.W.%'"); 
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE (fromloc LIKE '%.W.%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['strana_wn']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (fromloc LIKE '%.W.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> strana_wn.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "W - Zbývá";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromloc LIKE '%.W.%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                break;
            case isset($_POST['bridge']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' ORDER BY id ASC");
                ?><script> bridge.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Bridge";</script><?php
                $zapojenobridge = $conn->query("SELECT name, checked FROM `$napsano` WHERE name LIKE '%bridge%' AND (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $zapojenocelkem = $conn->query("SELECT name, checked FROM `$napsano` WHERE name LIKE '%bridge%' AND (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' ORDER BY id ASC");
                break;
            case isset($_POST['bridge_n']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> bridge_n.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Bridge - Zbývá";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                break;
            case isset($_POST['lanka']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%wiring-sorted%' ORDER BY id ASC");
                ?><script> lanka.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Lanka";</script><?php
                $zapojenocelkem = $conn->query("SELECT name, checked FROM `$napsano` WHERE (name LIKE '%wiring-sorted%') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['lanka_n']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%wiring-sorted%') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> lanka_n.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Lanka - Zbývá";</script><?php
                break;
            case isset($_POST['kabely']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE name='Cable-sorted' ORDER BY id ASC");
                ?><script> kabely.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Kabely"; </script><?php
                $zapojenocelkem = $conn->query("SELECT name, checked FROM `$napsano` WHERE (name='Cable-sorted') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['kabely_n']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (name='Cable-sorted') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> kabely_n.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Kabely - Zbývá"; </script><?php
                break;
            case isset($_POST['optiky']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE name='FO-sorted' ORDER BY id ASC");
                ?><script> optiky.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Optiky"; </script><?php
                $zapojenocelkem = $conn->query("SELECT name, checked FROM `$napsano` WHERE (name='FO-sorted') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['optiky_n']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE (name='FO-sorted') and (((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1))");
                ?><script> optiky_n.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Optiky - Zbývá"; </script><?php
                break;
            case isset($_POST['ws0']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE name='wiring-sorted0' ORDER BY id ASC");
                ?><script> ws0.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "WS0";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND name='wiring-sorted0') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND name='wiring-sorted0'"); 
                $zapojenocelkem = $conn->query("SELECT name, checked FROM `$napsano` WHERE (name='wiring-sorted0') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['ws1']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE name='wiring-sorted1' ORDER BY id ASC");
                ?><script> ws1.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "WS1";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND name='wiring-sorted1') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND name='wiring-sorted1'"); 
                $zapojenocelkem = $conn->query("SELECT name, checked FROM `$napsano` WHERE (name='wiring-sorted1') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['ws2']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE name='wiring-sorted2' ORDER BY id ASC");
                ?><script> ws2.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "WS2";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND name='wiring-sorted2') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND name='wiring-sorted2'"); 
                $zapojenocelkem = $conn->query("SELECT name, checked FROM `$napsano` WHERE (name='wiring-sorted2') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['ws3']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE name='wiring-sorted3' ORDER BY id ASC");
                ?><script> ws3.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "WS3";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND name='wiring-sorted3') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND name='wiring-sorted3'"); 
                $zapojenocelkem = $conn->query("SELECT name, checked FROM `$napsano` WHERE (name='wiring-sorted3') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['dvojdutinkyfrom']):
                $todos = $conn->query("SELECT * FROM `$napsano` where fromintext LIKE 'r%' and fromintext != 'R' and fromintext != 'RT' ORDER BY id ASC");
                ?><script> dvojdutinkyfrom.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Dvojdutinky From";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' AND fromintext LIKE 'r%' and fromintext != 'R' and fromintext != 'RT') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                $celkembridge = $conn->query("SELECT * FROM `$napsano` where fromintext LIKE 'r%' and fromintext != 'R' and fromintext != 'RT' and name LIKE '%bridge%' ORDER BY id ASC");
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE (fromintext LIKE 'r%' and fromintext != 'R' and fromintext != 'RT') and (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev and checked = 1))");
                break;
            case isset($_POST['vsechno']):
                $todos = $conn->query("SELECT * FROM `$napsano` ORDER BY id ASC");
                ?><script> vsechno.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Všechno";</script><?php
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%') AND (((checked is null or checked = 0) and (rev < $lowrev)) OR (rev >=$lowrev and checked =1))"); 
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%'"); 
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE ((checked is null or checked = 0) and (rev < $lowrev)) OR (rev >=$lowrev and checked =1)");
                break;
            case isset($_POST['zbyva']):
                $todos = $conn->query("SELECT * FROM `$napsano` WHERE ((checked is null or checked = 0) and rev >= $lowrev) or (rev < $lowrev and checked = 1)");
                ?><script> zbyva.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Zbývá";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE (name LIKE '%bridge%' and ((checked is null or checked = 0) and rev >= $lowrev)) or (name LIKE '%bridge%' and rev < $lowrev and checked = 1)");
                break;
            case isset($_POST['zmeny']):
                $todos = $conn->query("SELECT * FROM `$napsano` where (rev < $lowrev OR rev >= $lowrev AND rev < $highrev) ORDER BY fromloc ASC, fromdev ASC, length(frompin), frompin ASC, rev asc");
                ?><script> zmeny.classList.toggle("clicked"); dropbtn.classList.toggle("clicked"); dropbtn.innerText = "Změny";</script><?php
                $celkembridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' and (rev < $lowrev OR rev >= $lowrev AND rev < $highrev)");
                $zapojenobridge = $conn->query("SELECT * FROM `$napsano` WHERE name LIKE '%bridge%' AND (((checked is null or checked = 0) and rev < $lowrev) or (rev >= $lowrev AND rev < $highrev and checked=1))"); 
                $zapojenocelkem = $conn->query("SELECT fromloc, checked FROM `$napsano` WHERE ((checked is null or checked = 0) and rev < $lowrev) or ((rev >= $lowrev) AND rev < $highrev and checked =1)");
                break;
            
            default://pokud nebylo nic vybráno
            
                ?>
                <div class="empty2"> 
                - <?php echo $napsano;
                    
                    $todos = $conn->query("SELECT * FROM `$napsano` where todir LIKE '%unknown%' or todir LIKE '%top%' or todir LIKE '%bottom%' or todir LIKE '%left%' or todir LIKE '%right%'
                    or todir LIKE '%zleva%' or todir LIKE '%zprava%' limit 1");              
                    ?>
                    - <br>
                   <?php 
                        $je_e3 = $todos->rowCount();
                        if($je_e3 > 0){
                        ?> <a style="color:green;">CL je v E3 formě.</a> 
                        <br>Vyberte náhled, který chcete vidět.
                  <?php  } else {
                        ?> <a style="color:red;">CL není v E3 formě!!! Klikněte <a href="/connlistnone3/?search=<?php echo $napsano?>&submit=Hledej"> zde</a><a style="color:red;"> pro změnu zobrazení.</a> 
                 <?php }
                    
                 //pokud existuje BussWiring tak ho zobraz                
                if ( !empty($checkbw->num_rows) && $checkbw->num_rows > 0) //sem se dotázat zda v SQL jsou hodnoty, pokud jsou, zobraz tlačítko
                { ?> 
                <br><br>Rozváděč obsahuje buss wiring:<br>
                <button id="bw" onclick="location.href='index.php?search=<?php echo $bwnapsano; ?>&submit=Potvrď' ;" style="background-color:yellow; color:black; font-size:30px;cursor:pointer;">Přejít na BussWiring?</button>
                <?php } ?>
                </div> 
        <?php   die(); 
                break; 
        }  

        //spolecne hodnoty pri vyberu - spodni lista se souctem
        //pehozeni do promennych 
        if($zapojenobridge!=""){$zapojenobridgepcs = $zapojenobridge->rowCount();} else {$zapojenobridgepcs=0;}
        if($zapojenocelkem!=""){$zapojenocelkempcs = $zapojenocelkem->rowCount();} else {$zapojenocelkempcs=0;}
        if($celkembridge!=""){$celkembridgepcs = $celkembridge->rowCount();} else {$celkembridgepcs=0;}
        $celkemvse = $todos->rowCount();

        echo "Celkem: "
        //zapojeno celkem
        ?><p7 id="zapojeno"><?php echo $zapojenocelkempcs;?></p7><p7 style="margin-right:30px">/<?php echo $celkemvse;?></p7><?php
        //zapojeno bridge
        $bridgejs = $zapojenobridgepcs; ?> Bridge: <p7 id="bridgeid"><?php echo $bridgejs;?></p7>/<p7 style="margin-right:30px"><?php echo $celkembridgepcs;?></p7>Lanka: <?php 
        //zapojeno celkem
        $lankojs = $zapojenocelkempcs - $bridgejs; ?><p7  id="lankoid"><?php echo $lankojs;
        ?><script> var bridgejs = "<?php echo $bridgejs; ?>";var lankojs = "<?php echo $lankojs; ?>";</script></p7>/<?php
        //lanka celkem
        echo $celkemvse - $celkembridgepcs;

    }
    else
    {  
    ?>
    <div class="empty2"> 
    <h3> Zadejte název CL.</h3>
    </div>
    <?php
        die(); //bez hodnoty dál nepokračuj 
    }  
?>
</div>
       <div class="show-todo-section" id="show-todo-section">
            <?php if($todos->rowCount() <= 0){ ?>
                <div class="todo-item">
                    <div class="empty"> 
                        Vyberte jiný náhled, na tomto nic není. <img src="img/empty.jpg" width="37px">
                    </div>
                </div>
            <?php } ?>
            <!--vypsání hodnot z tabulky-->
            <?php 
            $kabel = "";    //definice pro neopakování stejného textu kabelu
            $bridge = "";    //definice pro neopakování stejného textu bridge
            $nazevsvorek = ""; //nazev svorek pro odělovač
            while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { 

             ///////////////////////////////////////////////////zjištění změn   
            $rev = "";
            if($cislorevize == 1){
                if($todo['rev'] < 1){
                    $rev = "rem";
                }
            } else if($todo['rev'] >= $lowrev && $todo['rev'] < $highrev){ //všechny revize ADD   //&& ($todo['rev'] % 2) == 0
                $rev = "add";
            } else if($todo['rev'] < $lowrev) { //všechny revize REMOVE
                $rev = "rem";
            } 
            ///////////////////////////////////////////////////////////////////////
            if($kabel != $todo['cname']) //Opakuje se hodnota kabelu?
            {
                ?><div class="kabel"><?php
                echo $todo['cname'];
                ?> - <?php echo $todo['ctype'];
                $kabel = $todo['cname'];
                ?></div><?php
            }?>
      <?php if(($bridge != $todo['wtype']) && ((strpos($todo['name'], 'Bridge') !== false)))//Opakuje se hodnota bridge?
            {
                ?><div class="kabel"><?php
                echo $todo['wtype'];
                $bridge= $todo['wtype'];
                ?></div><?php

            }
            if($nazevsvorek != $todo['fromdev']){
                
                ?><div class="svorka"><?php
                echo $todo['fromdev'];
                $nazevsvorek = $todo['fromdev'];
                ?></div><?php
            } ?>
 
<!------------------cele tělo propoje todo-item------------------------->
            
            <div class="todo-item" data-todo-id="<?php echo $todo['id'];?>"
                style="<?php if(($rev == "rem") && ($todo['checked'] == 0 ||  $todo['checked'] == null)){?>  <!--pointer-events: none;--> <?php } // pointer-events: none; pokud neni zapojeno REM,nemužeme zapojit?>">  
                <div> <!--konec rozmezi na filtr -->
                <div class="idsekce">
                    <span <?php echo $todo['name']; ?> ></span>
                    <div class="id">
                        <h2><?php echo $todo['nr']; ?></h2>
                    </div>
                    <div class="id">
                    <h2 id="rev<?php echo $todo['id']; ?>"<?php //zobrazit ADD nebo REM dle posledni změny
                    if($cislorevize == 1){
                        if($todo['rev'] < 1){
                            ?> style="color:red;font-weight: bold;"><?php echo "-R";
                        }
        
                    } else if($todo['rev'] >= $lowrev && $todo['rev'] < $highrev){ //všechny revize ADD   //&& ($todo['rev'] % 2) == 0
                        ?> style="color:green;font-weight: bold;"><?php echo "+R";
                    } else if($todo['rev'] < $lowrev) { //všechny revize REMOVE
                       ?> style="color:red;font-weight: bold;"><?php echo "-R";
                    } 

                    //označení baličku číslem
                    if(($todo['rev'] >= $lowrev && $todo['rev'] < $highrev) || ($todo['rev'] < $lowrev)){                   
                        $pole = array (0,1,2,1,3,3,2,1,4,4,4,4,3,3,2,1,5,5,5,5,5,5,5,5,4,4,4,4,3,3,2,1,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,5,5,5,5,5,5,5,5,4,4,4,4,3,3,2,1,7,7,
                        7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,5,5,5,5,5,5,5,5,4,4,4,4,3,3,2,1,8,8,8,8,8,8,8,8,8,8,8,8,
                        8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,
                        7,7,7,7,7,7,7,7,7,7,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,5,5,5,5,5,5,5,5,4,4,4,4,3,3,2,1,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,
                        9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,
                        9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,
                        8,8,8,8,8,8,8,8,8,8,8,8,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,5,5,5,5,5,5,5,5,4,4,4,4,3,3,
                        2,1,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,
                        10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,
                        10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,
                        10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,
                        10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,
                        10,10,10,10,10,10,10,10,10,10,10,10,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,
                        9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,8,8,
                        8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,7,7,7,7,7,7,7,7,7,7,7,7,
                        7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,5,5,5,5,5,5,5,5,4,4,4,4,3,3,2,1);
                        echo($pole[$todo['rev']]);  
                    }
                    ?></h2>
                    </div>
                </div>               
                    
            <p8 id="pocetjs<?php echo $todo['id']?>" class="hidden"> <?php echo $todo['name'] ?> </p8>
                 <!--check boxy, jejich ukladani do SQL -->
                      
                    <!--////////////CL////////////// -->           
                
                    <!--FROM strana zapojení -->
                    <?php if($todo['checked'])
                    { ?> <div id="sidef<?php echo $todo['id']?>" class="side checked <?php if(($rev == "rem") && ($todo['checked'] == 1)){ ?> red scroll<?php } ?>"> 
                    <?php } else
                    { ?> <div id="sidef<?php echo $todo['id']?>" class="side <?php if(($rev == "rem") && ($todo['checked'] != 1)){ ?> checked<?php } else { ?>scroll<?php } ?>">  <?php } ?>

                        <div class="fromloc">
                                    <?php echo $todo['fromloc'] ?>
                                    <br>
                            </div>
                            <div class="fromdev">
                                    <?php echo $todo['fromdev']?>:<?php echo $todo['frompin'] ?>
                            </div>
                        </div>

                    <!--FROM koncovka -->
                    <div class="fromfittside">
    
                                                   
                 <!--je to bridge? Pokud jo, tak koncovku nedavej.-->
                 <?php  if (strpos($todo['name'], 'Bridge') !== false){}else
                   {?>
                        <!--Otázky na typ koncovky-->
                         <?php  if ((strpos($todo['fromfitt'], 'W-end-slv') !== false) && (strpos($todo['fromfitt'], 'mm+ins') !== false)) {//otazka zda jde o dutinku s limcem ?>
                                <div class="fromfittferrule">
                                     <h2><?php echo substr($todo['fromfitt'], 10, -6); //Vypsání od znaku 10 a ze zadu odmazat 6 znaku ?></h2>
                                </div>
                        <?php }else if (strpos($todo['fromfitt'], 'mm-no-ins') !== false) { //otazka zda jde o dutinku bez limce ?>
                            <div class="fromfittferrulewc">
                                     <h2><?php echo substr($todo['fromfitt'], 10, -9);?></h2>
                                </div>
                        <?php }else if (strpos($todo['fromfitt'], 'Partial-bared-') !== false) { //otazka zda jde o odholeni ?>
                            <div class="fromfittwoferrule">
                                      <?php echo substr($todo['fromfitt'], 14, -2); ?>
                                </div> 
                        <?php }else if ((strpos($todo['fromfitt'], 'Rnd-lg-') !== false) && (strpos($todo['fromfitt'], '+ins') !== false ) && (strpos($todo['fromfitt'], 'SCHUKA') !== false)) { //ocko ins?>
                            <div class="fromfittlugins"> <img src="img/llugins.png" width="80px" height="30px">
                                      <?php  echo substr($todo['fromfitt'], 8, -15);?>angl,sc
                            </div> 
                        <?php }else if ((strpos($todo['fromfitt'], 'Rnd-lg-') !== false) && (strpos($todo['fromfitt'], '+ins') !== false)) { //ocko ins?>
                            <div class="fromfittlugins"> <img src="img/llugins.png" width="80px" height="30px">
                                      <?php  echo substr($todo['fromfitt'], 8, -4); ?>
                                </div> 
                        <?php }else if ((strpos($todo['fromfitt'], 'Rnd-lg-') !== false)) { //ocko?>
                            <div class="fromfittlugins"> <img src="img/llug.png" width="80px" height="30px">
                                      <?php  echo substr($todo['fromfitt'], 8, -4); ?>
                                </div> 
                        <?php }else if ((strpos($todo['fromfitt'], 'Fork-lug-') !== false) && (strpos($todo['fromfitt'], '+ins') !== false)) { //forklug ins?>
                            <div class="fromfittlugins"> <img src="img/lforglugins.png" width="80px" height="30px">
                                      <?php  echo substr($todo['fromfitt'], 9); ?>
                            </div> 
                        <?php }else if ((strpos($todo['fromfitt'], 'Hooked-blade') !== false) && (strpos($todo['fromfitt'], '+ins') !== false)) { //hooked blade lug?>
                            <div class="fromfittlugins"> <img src="img/lhookedbladeins.png" width="80px" height="30px">

                            </div> 
                        <?php }else if ((strpos($todo['fromfitt'], 'Blade-lug-') !== false) && (strpos($todo['fromfitt'], '+ins') !== false)) { //blade lug?>
                            <div class="fromfittlugins"> <img src="img/lbladelug.png" width="80px" height="30px">

                            </div> 
                        <?php }else if (strpos($todo['fromfitt'], 'Cmbflx-term-') !== false) { //combiflx?>
                            <div class="fromfittcombi"><img src="img/lcmbflx.png" width="80px" height="30px">
                                      <?php  echo substr($todo['fromfitt'], 16, -1); ?>
                                </div> 
                        <?php }else if ((strpos($todo['fromfitt'], 'Faston-connector') !== false) && (strpos($todo['fromfitt'], 'mm+ins') !== false)){ //faston ins?>
                            <div class="fromfittfaston"><img src="img/lfastonins.png" width="80px" height="30px">
                                      <?php  echo substr($todo['fromfitt'], 17, -6); ?>
                                </div> 
                        <?php }else {?>    
                                    <div class="fromfitt" style="font-size: 12px">
                                    <?php echo $todo['fromfitt']?>
                                    </div>      
                         <?php }
                    }?>  
                    </div>
                    
                    <!--Informace o vodiči -->
                    <div class="wire">
                        <!--Otázky na barvu vypisuj s pruřezem-->
                         <?php switch ($todo['wcolour']) {
                          case "Grey":?><div class="prurezwg"><h3>Grey <?php echo $todo['wgauge']; ?></h3></div><?php break;
                            case "Blue":?><div class="prurezwbl"><h3>Blue <?php echo $todo['wgauge']; ?></h3></div><?php break;
                            case "Red":?><div class="prurezwr"><h3>Red <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Black":?><div class="prurezwb"><h3>Black <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Yellow":?><div class="prurezwy"><h3>Yellow <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Green":?><div class="prurezwgr"><h3>Green <?php echo $todo['wgauge']; ?></h3></div> <?php break; 
                            case "White":?><div class="prurezww"><h3>White <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Green-Yellow":?><div class="prurezwgy"><h3>Green-Yellow <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Earth-connection":?><div class="prurezwgy"><h3>Earth-connection <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Brown":?><div class="prurezwbr"><h3>Brown <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Light-Blue":?><div class="prurezwlb"><h3>Light-Blue <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Bridge":?><div class="prurezwbridge"><h3><?php echo $todo['wtype'] ?></h3></div><?php break;
                            case "Optical fiber":?><div class="prurezwfo"><h3>FO <?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            case "Prewired":?><div class="prurezwp"><h3>Prewired!-<?php echo $todo['wnum'] ?></h3><h3><?php echo $todo['wgauge']; ?></h3></div><?php break; 
                            default: if (strpos($todo['name'], 'Bridge') !== false){ ?><div class="prurezwbridge"><h3><?php echo $todo['wtype'] ?></h3></div><?php
                            }else{                            
                                  ?><div class="prurezw"><h3><?php echo $todo['wcolour'] ?></h3><h3><?php echo $todo['wgauge']; ?></h3></div><?php break; //raději vypiš i hodnotu Wcolour 
                                  }
                            }?>
                        <!--vypiš inter exter a zabarveni dvojdutinek-->
                            <div class="fromintext">     
                                <?php
                                //zabarvi když je dvojdutinka
                                if ((stripos($todo['fromintext'], 'r') !== false) && ($todo['fromintext'] != 'R'))  { ?> <span style="background:yellow"> <?php } 

                                echo $todo["fromintext"];

                                ?> </span>
                            </div>
                        <!--Vypiš ještě délku a svazek -->
                            <div class="delkaw">
                                <h5><?php echo $todo['wlenght'] ?>mm
                            <?php if($todo['wnum'] != null){ ?> <h5 style="color:red; font-size:10px;">/<?php echo $todo['wnum']; ?> </h5><?php } ?></h5>  

                            </div>
                         <!--iner nebo exter a zabarveni dvojdutinek-->
                           <div class="toint">    
                             <?php
                                //zabarvi když je dvojdutinka
                                if ((stripos($todo['toint'], 'r') !== false) && ($todo['toint'] != 'R'))  { ?> <span style="background:yellow"> <?php } 

                                echo $todo["toint"];
                            
                                ?> </span>
                            </div>
                    </div>
                    <!--TO koncovka -->
                    <div class="tofittside">
                            

                 <!--je to bridge? Pokud jo, tak koncovku nedavej.-->
                 <?php  if (strpos($todo['name'], 'Bridge') !== false){}else
                   { ?>
                         <!--Otázky na typ koncovky--> 
                         <?php  if ((strpos($todo['tofitt'], 'W-end-slv') !== false) && (strpos($todo['tofitt'], 'mm+ins') !== false)) {//otazka zda jde o dutinku s limcem ?>
                                <div class="tofittferrule">
                                     <h2><?php echo substr($todo['tofitt'], 10, -6); //Vypsání jenom čisla delky ?></h2>
                                </div>
                        <?php }else if (strpos($todo['tofitt'], 'mm-no-ins') !== false) { //otazka zda jde o dutinku bez limce ?>
                            <div class="tofittferrulewc">
                                     <h2><?php echo substr($todo['tofitt'], 10, -9);?></h2>
                                </div>
                        <?php }else if (strpos($todo['tofitt'], 'Partial-bared-') !== false) { //otazka zda jde o odholeni ?>
                            <div class="tofittwoferrule">
                                      <?php echo substr($todo['tofitt'], 14, -2); ?>
                                </div> 
                        <?php }else if ((strpos($todo['tofitt'], 'Rnd-lg-') !== false) && (strpos($todo['tofitt'], '+ins') !== false) && (strpos($todo['tofitt'], 'SCHUKA') !== false)) { //ocko ins?>
                            <div class="tofittlugins"> <img src="img/rlugins.png" width="80px" height="30px">
                                        <?php  echo substr($todo['tofitt'], 8, -15);?>angl,sc
                            </div>
                        <?php }else if ((strpos($todo['tofitt'], 'Rnd-lg-') !== false) && (strpos($todo['tofitt'], '+ins') !== false)) { //ocko ins?>
                            <div class="tofittlugins"> <img src="img/rlugins.png" width="80px" height="30px">
                                      <?php  echo substr($todo['tofitt'], 8, -4); ?>
                                </div>
                        <?php }else if ((strpos($todo['tofitt'], 'Rnd-lg-') !== false)) { //ocko?>
                            <div class="tofittlugins"> <img src="img/rlug.png" width="80px" height="30px">
                                      <?php  echo substr($todo['tofitt'], 8, -4); ?>
                                </div> 
                        <?php }else if ((strpos($todo['tofitt'], 'Fork-lug-') !== false) && (strpos($todo['tofitt'], '+ins') !== false)) { //forklug ins?>
                            <div class="tofittlugins"> <img src="img/rforglugins.png" width="80px" height="30px">
                                      <?php  echo substr($todo['tofitt'], 9); ?>
                            </div> 
                            <?php }else if ((strpos($todo['tofitt'], 'Hooked-blade') !== false) && (strpos($todo['tofitt'], '+ins') !== false)) { //hooked blade lug?>
                            <div class="tofittlugins"> <img src="img/rhookedbladeins.png" width="80px" height="30px">

                            </div> 
                        <?php }else if ((strpos($todo['tofitt'], 'Blade-lug-') !== false) && (strpos($todo['tofitt'], '+ins') !== false)) { //blade lug?>
                            <div class="tofittlugins"> <img src="img/rbladelug.png" width="80px" height="30px">

                            </div> 
                         <?php }else if (strpos($todo['tofitt'], 'Cmbflx-term-') !== false) { //combiflx?>
                            <div class="tofittcombi"><img src="img/rcmbflx.png" width="80px" height="30px">
                                      <?php  echo substr($todo['tofitt'], 16, -1); ?>
                                </div>
                        <?php }else if ((strpos($todo['tofitt'], 'Faston-connector') !== false) && (strpos($todo['tofitt'], 'mm+ins') !== false)){ //faston ins?>
                            <div class="tofittfaston"><img src="img/rfastonins.png" width="80px" height="30px">
                                      <?php  echo substr($todo['tofitt'], 17, -6); ?> 
                                 </div>
                        <?php }else {?>  
                                    <div class="tofitt" style="font-size: 12px">
                                    <?php echo $todo['tofitt']?>
                                    </div> 
                         <?php }
                    } ?>   
                    </div>
                    <!--To strana zapojení -->
                <?php if($todo['checked'])
                    { ?> <div id="sidet<?php echo $todo['id']?>" class="side checked <?php if(($rev == "rem") && ($todo['checked'] == 1)){ ?> red<?php } ?>"> 
                
                <?php } else
                    { ?> <div id="sidet<?php echo $todo['id']?>" class="side<?php if(($rev == "rem") && ($todo['checked'] != 1)){ ?> checked<?php } ?>">  <?php } ?>

                          <div class="toloc">
                                <?php echo $todo['toloc'] ?>
                                <br>
                           </div>
                           <div class="todev">
                                <?php echo $todo['todev'] ?>:<?php echo $todo['topin'] ?>
                            </div>
                     </div>

                 <!--<small>created: <?php // echo $todo['date_time'] ?></small>  Datum zatím neřeš.-->

                 <div class="cestaw">
                        <h4><?php echo $todo['signalv'] ?></h4>
                        <h4 style="font-size:11px;word-break: break-all;"><?php echo $todo['cdpath'] ?></h4>    
                </div>
                <div class="ref">
                    <p7> <?php echo $todo['fromref']; ?> </p7>
                    <p7><?php echo $todo['toref']; ?> </p7>
                    <h2 style="font-size:10px" id="loged<?php echo $todo['id']?>"> <?php echo $todo['zapojil']; ?> </h2>
                </div>
                </div> <!--konec rozmezi na filtr -->
            </div> <!--konec TODO item -->


            <?php } ?>
       </div>
    </div>

<script src="js/jquery-3.2.1.min.js"></script>

<script>

//scrolni k vodici co neni zapojeny
function scrollNaVodic() {

    if(!$('.scroll:visible:first').offset()){
        alert("Na stránce už nic k práci není :-).")
    }

    $('html, body').animate({
        scrollTop: $('.scroll:visible:first').offset().top-70
    }, 500); 

}

$(document).ready(function(){

    //otevřeni legendy
    let sipka = false;
    $("#info").click(function(e){

        if(sipka == true){
            $(".sipka").fadeOut('slow');
            sipka = false;
        } else {
            $(".sipka").fadeIn('slow');
            sipka = true;
        }

    });

    //zapojeni vodiče
    $(".todo-item").click(function(e){
        const id = $(this).attr('data-todo-id');
        const tabulka = '<?php echo $napsano; ?>';
        const user = '<?php echo $user; ?>';
        const revize = '<?php echo $hledanarev; ?>';
        const zapojovat = '<?php echo $zapojovat; ?>';
        const prl = '<?php echo $prlklik; ?>';

        if(zapojovat == false){
            alert("Nemáte právo zapojovat.");
        } else if(prl == 0){
            alert("Nejprve potvrďte rázítko v PRL! Poté obnovte stránku, nebo vyberte znovu náhled.");
        } else {

            $.post('app/check.php', //zapis do Database
                    {
                        id: id,
                        tabulka: tabulka, //prenes napsanou hodnotu projektu
                        revize: revize, //jakou revizi zpracováváme
                        user: user //přenes jmeno uzivatele
                    },
                    (data) => { //z checku zjisti jake ID budem zpracovavat
                        if(data != 'error'){

                            if(data === 'novarev'){  

                                alert("POZOR!!! Byla nahraná nová verze CL. Obnovte stránku, nebo vyberte znovu pohled!");

                            } else {    

                                var sidef = document.getElementById("sidef"+id); //vyhledat ID v klasifikacich s hodnotou SIDE
                                var sidet = document.getElementById("sidet"+id); //vyhledat ID v klasifikacich s hodnotou SIDE
                                    
                                let vysledekjs = document.getElementById("pocetjs"+id).textContent; //text v typu vodiče (bridge sorted?)
                                let logedjs = document.getElementById("loged"+id).innerHTML = user; //kdo zapojil, rovnou přepisuj
                                let zapojeno = document.getElementById("zapojeno").innerHTML; //aktualni hodnota zapojeno komplet

                                let rem = document.getElementById("rev"+id).textContent; //jedna se o REM vodič?
                                                        
                                if(data === '1'){

                                    //odebrat klasifikaci se zelenou barvou

                                    if(rem.includes('-R')){ //je to remove propoj, tak ho misto bile zazeleníš

                                            sidef.classList.remove("red");
                                            sidet.classList.remove("red");
                                            sidef.classList.add("checked");
                                            sidet.classList.add("checked");

                                            sidef.classList.remove("scroll");

                                            if (vysledekjs.includes('Bridge')) { //jde o bridge?

                                                bridgejs++;
                                                zapojeno++;
                                                bridgeid.innerText = bridgejs; //aktualizuj hodnotu
                                                let zapojeno_update = document.getElementById("zapojeno").innerHTML = zapojeno;

                                            } 
                                            else
                                            {
                                                lankojs++;
                                                zapojeno++;
                                                lankoid.innerText = lankojs; //aktualizuj hodnotu
                                                let zapojeno_update = document.getElementById("zapojeno").innerHTML = zapojeno;
                                            }

                                        } else {

                                            sidef.classList.remove("checked");
                                            sidet.classList.remove("checked");

                                            sidef.classList.add("scroll");

                                            if (vysledekjs.includes('Bridge')) { //jde o bridge?

                                                bridgejs--;
                                                zapojeno--;
                                                bridgeid.innerText = bridgejs; //aktualizuj hodnotu
                                                let zapojeno_update = document.getElementById("zapojeno").innerHTML = zapojeno;

                                            } 
                                            else
                                            {
                                                lankojs--;
                                                zapojeno--;
                                                lankoid.innerText = lankojs; //aktualizuj hodnotu
                                                let zapojeno_update = document.getElementById("zapojeno").innerHTML = zapojeno;
                                            }
                                        }

                                   
                                } else if((data === '0' || data == '')) {
                                    //přidat clasifikaci 

                                    if(rem.includes('-R')){ //je to remove propoj, tak ho misto bile zazeleníš

                                            sidef.classList.remove("checked");
                                            sidet.classList.remove("checked");
                                            sidef.classList.add("red");
                                            sidet.classList.add("red");

                                            sidef.classList.add("scroll");

                                            if (vysledekjs.includes('Bridge')) { 
                                        
                                                bridgejs--;
                                                zapojeno--;
                                                bridgeid.innerText = bridgejs;
                                                let zapojeno_update = document.getElementById("zapojeno").innerHTML = zapojeno;
                                            }  
                                            else 
                                            {
                                                lankojs--;
                                                zapojeno--;
                                                lankoid.innerText = lankojs;
                                                let zapojeno_update = document.getElementById("zapojeno").innerHTML = zapojeno;
                                            } 

                                        } else {

                                            sidef.classList.add("checked");
                                            sidet.classList.add("checked");                                           
                                            
                                            sidef.classList.remove("scroll");

                                            if (vysledekjs.includes('Bridge')) { 
                                        
                                                bridgejs++;
                                                zapojeno++;
                                                bridgeid.innerText = bridgejs;
                                                let zapojeno_update = document.getElementById("zapojeno").innerHTML = zapojeno;
                                            }  
                                            else 
                                            {
                                                lankojs++;
                                                zapojeno++;
                                                lankoid.innerText = lankojs;
                                                let zapojeno_update = document.getElementById("zapojeno").innerHTML = zapojeno;
                                            } 
                                        }
                            

                                }
                            }
                        }
                     }
                 );
            }
    });
});


    function filtr(){
    
    var filterValue, input, ul,li,a,i;
    input = document.getElementById("filtr");
    filterValue = input.value.toUpperCase();
    ul = document.getElementById("show-todo-section");
    li = ul.getElementsByClassName("svorka");
        
        for (i = 0 ; i < li.length ; i++){

            a = li[i].getElementsByTagName("div")[0];
            if(a.innerHTML.toUpperCase().indexOf(filterValue) > -1){
                li[i].style.display = "";
                
            }else{
                li[i].style.display = "none";
            }

        }
    }

// //zmenšení textu při zaplnění div
// function resizeText() {
//   const elements = document.querySelectorAll('.fromdev, .todev, .cestaw');
  
//   elements.forEach(element => {
//     let fontSize = parseFloat(window.getComputedStyle(element).fontSize);
    
//     while (element.scrollWidth > element.clientWidth || element.scrollHeight > element.clientHeight) {
//       fontSize -= 0.5;
//       element.style.fontSize = `${fontSize}px`;
//     }
//   });
// }
// resizeText();


    </script>
</body>
</html>