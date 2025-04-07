<?php 
require '../db_conn.php';
require '../logincheck.php';
$user = $_SESSION["user"];
$role = $_SESSION["role"];
$stranka = "pml"; //tento odkaz v baru zmizí

//role vypsané níže mají právo editovat
if($role == 9 || $role == 3) { $editor = true; } else { $editor = false; }
//role pro mazání
if($role == 9 || $role == 3) { $mazac = true; } else { $mazac = false; }

////////////////////////////////////////////////////////////////////////
$napsano = ""; //definice co uživatel napsal za projekt
$napsanoprojekt = ""; //definice projektu
$winfoobsah = "";

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/ppep3.png">
    <title>PML</title>
    <link rel="stylesheet" href="../loading.css?v=1">
    <link rel="stylesheet" href="css/style.css?v=7">

    <!-- jquery a sweetalert -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../js/sweetalert2.min.css">
</head>
<body>

<?php        
if (isset($_GET["submit"])) {
    //seber zadanou hodnotu
    $napsano = $_GET["search"];

    //nahradíme podtržítko za pomlčku při úklepu
    $napsano = str_replace("_", "-", $napsano);

    //zjisti číslo zakázky
    $data = $conn->query("SELECT zakazka FROM rffseznam WHERE item = '$napsano'");
    if($data !== false) {
        $row = $data->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            $zakazka = $row['zakazka'];
        } else {
            $zakazka = "";
        }
    }
    ?>

</div>  <div class="main-section"> <?php
    if($napsano == null){
        ?> <div class="uvodka"><br><h style="font-size: 40px;">Package materials</h><br> <h style="color:red">Nebylo nic napsáno v poli hledej. </h></div> 
        <?php
    }
    //zde je třeba hodit todos a naloadovat
    $todos = $conn->query("SELECT * FROM pml where project = '$napsano' ORDER BY id ASC");
    require '../menubar1.php'; //vygenerování odkazu do baru

    ?><script> let title_el = document.querySelector("title");
    const napsano = '<?php echo $napsano;?>'
    title_el.innerHTML = "PML " + napsano;

    </script><?php
}
else
{  
    if($napsano == null){ ?>
        <?php require '../menubar2.php'; //pokud není nic zvoleno odkazy defaultně  ?>
        <div class="uvodka"> 
            <br> 
            <h style="font-size: 40px;">Package materials</h> 
            <br> Napište číslo projektu-itemu do pravého horního rohu.
        </div>

        <div class="main-section" id="Menu"> <!-- Přidáme ID Menu na celý kontejner -->

            <!-- Vypíšeme seznam itemů -->
            <?php
            $todos = $conn->query("SELECT project from pml where (LENGTH(project) = 9 or LENGTH(project) = 11)
            and `date_zalozil` > DATE_SUB(NOW(), INTERVAL 4 MONTH)
            GROUP BY `project`
            ORDER BY `project`");

            while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                
            <div class="seznampole"> <!-- Používáme validní <div> -->
                <div class="sekce">
                    <a style="text-transform: uppercase;" href='/pml/?search=<?php echo $todo['project'] ?>&submit=Potvrď'>
                        <?php echo $todo['project'] ?>
                    </a>
                </div>
                <div class="sekce2">
                    <a href='/pml/?search=<?php echo $todo['project'] ?>&submit=Potvrď' target="_blank"> 
                        <img height="20px" border="0" src="img/tab.png" /> 
                    </a>
                </div>
            </div>
            
        <?php } ?>

        </div>
        <?php 
        }
}  ?>

 <!--loading image-->
<div class='spinner-wrapper'>
    <img src="/loading.gif" width="80px">
</div>

<!-- Navbar HTML -->
<?php  require '../menubar.php'; ?>
       <script>
            let spinnerWrapper = document.querySelector('.spinner-wrapper');
                window.addEventListener('load', function () {
                // spinnerWrapper.style.display = 'none';
                spinnerWrapper.parentElement.removeChild(spinnerWrapper);
            });
        </script>
<?php

if (isset($_POST['submit'])) {

    // Ošetření vstupů
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $pcs = isset($_POST['pcs']) ? htmlspecialchars(trim($_POST['pcs'])) : '';
    $sap = isset($_POST['sap']) ? htmlspecialchars(trim($_POST['sap'])) : '';
    $note = isset($_POST['note']) ? htmlspecialchars(trim($_POST['note'])) : '';
    
    // Validace délky proměnné $napsano
    if (strlen($napsano) != 9 && strlen($napsano) != 10 && strlen($napsano) != 11) {
        echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Chyba!',
            text: 'Projekt-item musí obsahovat standardních 9 nebo 10, 11 znaků. Vzor: CB001-001, 109240R1001'
        });
        </script>";
    } else {
        // Připravíme SQL dotaz
        if ($name == "" && $pcs == "" && $sap == "" && $note == "") {
            // Pokud jsou všechny hodnoty prázdné, použijeme místo nich defaultní hodnoty
            $stmt = $conn->prepare("INSERT INTO pml (project, name, pcs, sap, note, vytvoril) VALUES (:project, '-', '-', '-', '-', '-')");
        } else {
            // Pokud některé hodnoty nejsou prázdné, vložíme skutečné hodnoty
            $stmt = $conn->prepare("INSERT INTO pml (project, name, pcs, sap, note, vytvoril) VALUES (:project, :name, :pcs, :sap, :note, :user)");
            // Bindování parametrů
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':pcs', $pcs, PDO::PARAM_STR);
            $stmt->bindParam(':sap', $sap, PDO::PARAM_STR);
            $stmt->bindParam(':note', $note, PDO::PARAM_STR);
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        }
        
        // Bindování parametru pro projekt
        $stmt->bindParam(':project', $napsano, PDO::PARAM_STR);

        // Spuštění dotazu
        if ($stmt->execute()) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Založeno',
                showConfirmButton: false,
                timer: 700,
                timerProgressBar: true
            }).then((result) => {
                location.reload();
            });
            </script>";
        } else {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Chyba při ukládání!',
                text: 'Došlo k chybě při ukládání dat do databáze.'
            });
            </script>";
        }
    }
}

if($napsano != null) { ?>
    
       <div class="add-section">
            <div class="add-info">
                <?php

                // jde o nový název projektu?
                if (strlen($napsano) >= 6 && ctype_digit($napsano[0])) {
                    //seber prvních 6 znaku abychom věděli číslo projekt
                    $napsanoprojekt = substr($napsano, 0, 6);
                } else {
                    $napsanoprojekt = substr($napsano, 0, 5);
                }

                //sebraní hodnoty z database
                $winfohodnotadoc = $conn->query("SELECT * FROM pml where project = '$napsanoprojekt' order by dokumentace DESC limit 1");
                //spočítání řádku, pokud máme nulu, projekt se musí založit
                $winfoobsah = $winfohodnotadoc ->rowCount();
                ?>
                <div style="display:flex;">
                    <p style="font-size:26px;margin-left:17px;"><b>Package materials:</b></p>

                    <div class="no-print" id="arrow_down"></div> 
                        <div>
                            <a2 style="color: green; text-transform: uppercase; font-size:25px;"><b><?php echo $napsano;  ?></b></a2> <br>
                            <small style="font-size:15px; margin-left:15px;">(<?php echo $zakazka;?>)</small>
                        </div>
                    <div class="no-print" id="arrow_up"></div>
                 </div>

                <?php  if($winfoobsah == 0 ) { // nemáme hodnoty, vypíšeme template?>
                    <div class="info-rada">
                    <div class="info-informace">Documentation:</div>
                    <div id="infohodnotadokumentace" class="info-hodnota">???</div>
                    <div class="info-komentar"><?php if($editor){ ?><input id="infocommentdoc" type="text" maxlength="40" value=""></input>
                        <?php } else { echo ""; } ?>
                    </div>
                </div>
                <div class="info-rada">
                    <div class="info-informace">Set of yellow labels:</div>
                    <div id="infohodnotalabels" class="info-hodnota">???</div>
                    <div class="info-komentar"><?php if($editor){ ?><input id="infocommentlabels" type="text" maxlength="40" value="For missing devices"></input>
                        <?php } else { echo "For missing devices"; } ?>
                    </div>
                </div>
                <div class="info-rada">
                    <div class="info-informace">Plastic rail with label:</div>
                    <div id="infohodnotarail" class="info-hodnota">???</div>
                    <div class="info-komentar"><?php if($editor){ ?><input id="infocommentrail"  type="text" maxlength="40" value="For roof cabinet - RESP"></input>
                        <?php } else { echo "For roof cabinet - RESP"; } ?>
                    </div>
                </div>
                <div class="info-rada">
                    <div class="info-informace">Covers for bottom:</div>
                    <div id="infohodnotacovers" class="info-hodnota">???</div>
                    <div class="info-komentar"><?php if($editor){ ?><input id="infocommentcovers" type="text" maxlength="40" value="ACC. documentation"></input>
                        <?php } else { echo "ACC. documentation"; } ?>
                    </div>
                </div>  
                 <?php } else { while($winfohodnotadoc2 = $winfohodnotadoc->fetch(PDO::FETCH_ASSOC)) { ?> <!-- //hodnoty jsme našli, vypíšeme -->
                                   
                    <div class="info-rada">
                        <div class="info-informace">Documentation:</div>
                        <div id="infohodnotadokumentace" class="info-hodnota"> <?php echo $winfohodnotadoc2['dokumentace']; ?> </div>
                        <div class="info-komentar"><?php if($editor){ ?><input id="infocommentdoc" type="text" maxlength="40" value="<?php echo $winfohodnotadoc2['doccomment']; ?>"></input>
                            <?php } else { echo $winfohodnotadoc2['doccomment']; } ?>
                        </div>
                    </div>
                    <div class="info-rada">
                        <div class="info-informace">Set of yellow labels:</div>
                        <div id="infohodnotalabels" class="info-hodnota"><?php echo $winfohodnotadoc2['labels']; ?></div>
                        <div class="info-komentar"><?php if($editor){ ?><input id="infocommentlabels" type="text" maxlength="40" value="<?php echo $winfohodnotadoc2['labelscomment']; ?>"></input>
                            <?php } else { echo $winfohodnotadoc2['labelscomment']; } ?>
                        </div>
                    </div>
                    <div class="info-rada">
                        <div class="info-informace">Plastic rail with label:</div>
                        <div id="infohodnotarail" class="info-hodnota"><?php echo $winfohodnotadoc2['rail']; ?></div>
                        <div class="info-komentar"><?php if($editor){ ?><input id="infocommentrail" type="text" maxlength="40" value="<?php echo $winfohodnotadoc2['railcomment']; ?>"></input>
                            <?php } else { echo $winfohodnotadoc2['railcomment']; } ?>
                        </div>
                    </div>
                    <div class="info-rada">
                        <div class="info-informace">Covers for bottom:</div>
                        <div id="infohodnotacovers" class="info-hodnota"> <?php echo $winfohodnotadoc2['covers']; ?></div>
                        <div class="info-komentar"><?php if($editor){ ?><input id="infocommentcovers" type="text" maxlength="40" value="<?php echo $winfohodnotadoc2['coverscomment']; ?>"></input>
                            <?php } else { echo $winfohodnotadoc2['coverscomment']; } ?>
                        </div>
                    </div>  
                       <?php }  
                     } ?>
                    <button class="save-button" style="display:none;" type="submit" name="info-submit" id="info-submit"></button>               
            </div>
            <!-- editujeme -->
           <?php if($editor){?>
            <form class="no-print" method="POST" autocomplete="off">
                <input type="text" name="name" maxlength="46" placeholder="Name" />
                <input type="text" name="pcs"maxlength="4" placeholder="pc" />
                <input type="text" name="sap" maxlength="22" placeholder="SAP" />
                <input type="text" name="note" maxlength="45" placeholder="Note" />
                <button class="add-zaznam" type="submit" name="submit">Add &nbsp; <span>&#43;</span></button>
            </form>
 
          <?php } else { ?>
                    <div style="width:800px;display:block;"></div>
        <?php  } ?>
            <div class="add-info2 no-print">
                <?php if($editor) { ?>
                <form  method="POST">
                    <input class="kopie" type="text" name="nazev_kopie" placeholder="Target" />
                    <button class="kopie_btn" type="submit" name="kopie" >Copy</span></button>
                </form>
                <div class="save-button"> <img  src='img/save.png'> </div>

                <?php if (isset($_POST["kopie"])) {
                    //seber napsaný text
                    $kopie_text = isset($_POST["nazev_kopie"]) ? htmlspecialchars(trim($_POST["nazev_kopie"])) : null;

                        if($kopie_text == ""){

                            echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Tak to ne!',
                                text: 'Napište kam chcete kopirovat!'
                            })
                            </script>";

                        } else if (strlen($kopie_text ) != 9 && strlen($kopie_text ) != 11){

                            echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Chyba!',
                                text: 'Projekt-item musí obsahovat standardních 9 nebo 11 znaků. Vzor: CB001-001, 109240R1001'
                            });
                            </script>";

                        } else {     
                            //vytvoř dočasnou tabulku
                            $kopirovani = $conn->query("CREATE TABLE temp_table AS SELECT * FROM pml WHERE project='$napsano'");
                            //změn v dočasné tabulce název projektu na chtěný
                            $kopirovani = $conn->query("UPDATE temp_table SET project='$kopie_text', vytvoril='$user', zmenil='',
                            date_zalozil=current_timestamp(), date_upravil=null");
                            //nastav ID hodnoty na nulové, aby nedošlo ke kolizi
                            $kopirovani = $conn->query("UPDATE temp_table SET ID=NULL;");
                            //vlož upravené hodnoty zpět do tabulky pml
                            $kopirovani = $conn->query("INSERT INTO pml SELECT * FROM temp_table;");
                            //dočasnou tabulku smaž
                            $kopirovani = $conn->query("DROP TABLE temp_table;");       
                        } ?> </a><?php }
                
                     } 
                  $pmlpmodkaz = "pm.php?search=$napsano&submit=Hledej"; ?>                                              
                <div class="no-print" style="text-align:center; margin-top:1px;"><a href="<?php echo $pmlpmodkaz ?>">All<br>Items</a> </div>
                <?php if(!$editor){?>
                        <button style="margin-top: 50px; float:right;" class="tlacitko no-print" onclick="window.print()">Tisk</button>
                <?php } else { ?>
                        <button style="margin-top: 10px;" class="tlacitko no-print" onclick="window.print()">Tisk</button>
                <?php } ?>
            </div>
        </div>

        <div class="hlavicka">
            <div class= "hlavicka1 name">Name</div>
            <div class= "hlavicka1 pcs">Pcs</div>
            <div class= "hlavicka1 sap">Sap</div>
            <div class= "hlavicka1 note">Note</div>
        </div>
            
<!--vypsání hodnot z tabulky-->
            <?php
            while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <div class="vrchnirada">
                            <!-- editujeme -->
                <?php if($editor) { ?> 
                            <input id= "name<?php echo $todo['id']?>" data-id="<?php echo $todo['id']?>" class="jednopoletext name" type="text" name="name" maxlength="46" value="<?php echo $todo['name']; ?>" />
                            <input id= "pcs<?php echo $todo['id']?>" class="jednopoletext pcs" type="text" name="pcs" maxlength="4"value="<?php echo $todo['pcs']; ?>" />
                            <input id= "sap<?php echo $todo['id']?>" class="jednopoletext sap" type="text" name="sap" maxlength="22" value="<?php echo $todo['sap']; ?>" />
                            <input id= "note<?php echo $todo['id']?>" class="jednopoletext note" type="text" name="note" maxlength="45" value="<?php echo $todo['note']; ?>" />              
                                
                <?php     } else { ?>
                            <!-- nelze editovat -->
                            <div class= "jednopoletext name"><?php echo $todo['name'] ?></div>
                            <div class= "jednopoletext pcs"><?php echo $todo['pcs'] ?></div>
                            <div class= "jednopoletext sap"><?php echo $todo['sap'] ?></div>
                            <div class= "jednopoletext note"><?php echo $todo['note'] ?></div>
                        <?php } ?>
                    </div>

                    <div class="spodnirada no-print">
                    <?php if($mazac) { ?>
                        <div class="poledatum" style="width:30px">
                            <small id="<?php echo $todo['id']; ?>"  class="remove-to-do">x</small>
                        </div>
                            <?php } ?>   
                        <div class="poledatum">
                            <small style="color:red;">Created by: <?php echo $todo['vytvoril'] ?></small>
                            <small style="color:red;">  <?php echo $todo['date_zalozil'] ?></small>
                        </div>    
                        <div class="poledatum">
                        <?php if($todo['zmenil'] != null) { ?>
                            <small style="color:blue" id="loged_vyresil<?php echo $todo['id']?>">Edited by: <?php echo $todo['zmenil'] ?></small>
                            <small id="datum_vyresil<?php echo $todo['id']?>" style="color:blue;">  <?php echo $todo['date_upravil'] ?></small>
                            <?php } ?>
                        </div>
                    </div> 
                </div>       
                
            <?php } //konec while ?>
<?php } //konec bez hodnoty - nic si nevypsal?>

<script>

$(document).ready(function(){
    
    //Překlikávání hodnot z Yes na No
      $(".info-hodnota").click(function(){
        const editor = '<?php echo $editor; ?>';
        if(editor){
            if(this.innerText === "Yes"){
            this.innerText = "No"; 
            } else {
                this.innerText = "Yes"; 
            } 
        }
    });     

    //uložení hodnot vypsaných v hlavičce
    $(".save-button").click(function(e){
        const user = '<?php echo $user; ?>';
        const projekt = '<?php echo $napsanoprojekt; ?>';
        const zalozeniProjektu = '<?php echo $winfoobsah; ?>';

        var infohodnotadokumentace = document.getElementById("infohodnotadokumentace").innerText;
        var infohodnotalabels = document.getElementById("infohodnotalabels").innerText;
        var infohodnotarail = document.getElementById("infohodnotarail").innerText;
        var infohodnotacovers = document.getElementById("infohodnotacovers").innerText;

        var infocommentdoc = document.getElementById("infocommentdoc").value;
        var infocommentlabels = document.getElementById("infocommentlabels").value;
        var infocommentrail = document.getElementById("infocommentrail").value;
        var infocommentcovers = document.getElementById("infocommentcovers").value;

        $.post('app/infoedit.php', // zápis do databáze
            {
                user: user,
                projekt: projekt,
                zalozeniProjektu: zalozeniProjektu,
                infohodnotadokumentace: infohodnotadokumentace,
                infohodnotalabels: infohodnotalabels,
                infohodnotarail: infohodnotarail,
                infohodnotacovers: infohodnotacovers,
                infocommentdoc: infocommentdoc,
                infocommentlabels: infocommentlabels,
                infocommentrail: infocommentrail,
                infocommentcovers: infocommentcovers,
            },
            function(response) {
                // Zkontrolujeme odpověď od serveru
                if (response == 1) {
                    Swal.fire({
                        title: 'Uloženo!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        timer: 1000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload(); // Obnoví stránku po zobrazení upozornění
                    });
                } else {
                    Swal.fire({
                        title: 'Chyba!',
                        text: data,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        );
    });

    $('.remove-to-do').click(function () {
        const id = $(this).attr('id');

        Swal.fire({
            icon: 'question',
            title: 'Opravdu chcete smazat tento řádek?',
            showCancelButton: true,
            confirmButtonText: 'Ano',
            cancelButtonText: 'Ne'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("app/remove.php", {
                    id: id
                }, (data) => {
                    if (data) {

                        $(this).parents().eq(2).hide(600);
                    }
                });
            }
        });
    });

    // Zpracování stisku klávesy Enter na textových polích
    $(".jednopoletext").on('keydown', function(event) {
        if (event.key === "Enter") {
            const id = $(this).closest('div').find('.name').attr('data-id');
            const user = '<?php echo $user; ?>';

            // Pokud není ID, nic neprováděj
            if (!id) {
                console.log("Chyba: ID není k dispozici.");
                return;
            }

            var name = document.getElementById("name"+id).value;
            var pcs = document.getElementById("pcs"+id).value;
            var sap = document.getElementById("sap"+id).value;
            var note = document.getElementById("note"+id).value;

            $.post('app/edit.php', //zapis do Database
                {
                    id: id,
                    user: user, //přenes jmeno uzivatele
                    name: name,
                    pcs: pcs,
                    sap: sap,
                    note: note
                },
                (data) => { 
                        let title, message, icon;
                        if (data === '1') {
                            title = 'Editace byla uložena :-)'; 
                            message = 'Uložení proběhlo úspěšně.';
                            icon = 'success';
                        } else {
                            title = 'Chyba!'; 
                            message = 'Uložení pravděpodobně neproběhlo.';
                            icon = 'error';
                        }

                        Swal.fire({
                            icon: icon,
                            title: title,
                            text: message,
                            timer: 1000,
                            timerProgressBar: true
                        }).then((result) => {
                            location.reload();
                        });
                }
            );
        }
    });

    // filtrování v uvodním seznamu

        // Přidání eventu na input pro filtrování
        document.getElementById('placeholder').addEventListener('input', function() {
        let filter = this.value.toUpperCase(); // Získá hodnotu z inputu a převede ji na velká písmena
        let items = document.getElementsByClassName('seznampole'); // Získá všechny elementy s třídou 'seznampole'

        // Projdi všechny seznampole a zkontroluj, zda obsahují filtrovací hodnotu
        for (let i = 0; i < items.length; i++) {
            let projectName = items[i].querySelector('.sekce a').textContent || items[i].querySelector('.sekce a').innerText;

            // Pokud projekt obsahuje hledaný text, zobraz ho, jinak skryj
            if (projectName.toUpperCase().indexOf(filter) > -1) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }
    });

            
}); //konec DOMO click elementu

 //nastavit placeholder dle zadání
var placeholdervalue = "<?php echo $napsano ?>";
var placeholderjs = document.getElementsByName('search')[0].value=placeholdervalue;

//ochrana proti resubmitu (nechceme odesilat znova)
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}


/////////////////////////// šipky na překlikávání //////////////////////////////////
// Získání elementů šipek pomocí ID
const incrementArrow = $('#arrow_up');
const decrementArrow = $('#arrow_down');

// Přidání posluchače události kliknutí na inkrementační šipku
incrementArrow.on('click', function() {
    updateUrl(1); // Přidat 1 ke číslové hodnotě v URL parametru
});

// Přidání posluchače události kliknutí na dekrementační šipku
decrementArrow.on('click', function() {
    updateUrl(-1); // Odebrat 1 od číslové hodnoty v URL parametru
});

function updateUrl(value) {
    // Získání textu z proměnné $napsano
    const napsanoValue = "<?php echo $napsano; ?>";

    // Regulární výraz pro nalezení posledního čísla v řetězci
    const regex = /(\d+)(?!.*\d)/;

    // Nalezení posledního čísla v řetězci
    const match = napsanoValue.match(regex);

    if (match) {
        // Získání čísla z textu
        let number = parseInt(match[0]);

        // Aktualizace čísla
        number += value;

        // Přidání nul před číslo, pokud je menší než 100
        const formattedNumber = number.toString().padStart(match[0].length, '0');

        // Nahrazení posledního čísla v původním textu novým číslem
        const updatedValue = napsanoValue.replace(regex, formattedNumber);

        // Změna URL s aktualizovaným parametrem
        const newUrl = `/pml/?search=${updatedValue}&submit=Potvrď`;

        // Přesměrování na novou URL
        window.location.href = newUrl;
    } 
}


</script>

</body>
</html>