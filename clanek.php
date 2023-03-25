<?php
ob_start();

require_once "temp/header.php";
require_once "temp/menu.php";

$clanek = str_replace(".php","",$_GET['clanek']);
$kategorie = $_GET["kategorie"];

$json = file_get_contents('./clanky.json');
$json_data = json_decode($json,true);


if(!empty($kategorie)){
    foreach ($json_data as $key => $value) {
        if($json_data[$key]["url"] == $clanek && $json_data[$key]["tag_url"] == $kategorie){
            $thisClanek = $json_data[$key];
        }
    }
}else{
    foreach ($json_data as $key => $value) {
        if($json_data[$key]["url"] == $clanek){
            $thisClanek = $json_data[$key];
        }
    }
}


if($thisClanek == NULL or empty($thisClanek)){
    header("Location: /clanky");
}

$similar = explode(',', $thisClanek['similar']);

$buffer=ob_get_contents();
ob_end_clean();

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$actual_link_without_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$articleGraph = '
   <script type="application/ld+json">
   {
   "@context": "https://schema.org",
   "@type": "Article",
   "mainEntityOfPage": {
   "@type": "WebPage",
   "@id": "'.$actual_link.'"
   },
   "headline": "'.$thisClanek["topic"].' - Ekologickyuklid.eu",
   "image": "'.$actual_link_without_uri.$thisClanek['image'].'",
   "author": "Tým autorů ekologického úklidu",
   
   "publisher": {
           "@type": "Organization",
           "legalName": "EkoBIT, spol. s.r.o.",
           "description": "Ekologické úklidové služby",
           "url": "https://www.ekologickyuklid.eu/",
           "logo": "https://www.ekologickyuklid.eu/img/ekobit_logo.png"
       },
   "datePublished": "'.$thisClanek['date-html'].'",
   "dateModified": "'.$thisClanek['date-html'].'"
   }
   </script>
   ';

$buffer=str_replace("%TITLE%",$thisClanek['topic'] . " - Ekologickyuklid.eu" ,$buffer);
$buffer=str_replace("%DESCRIPTION%",$thisClanek['description'],$buffer);
$buffer=str_replace("%ARTICLE_GRAPH%",$articleGraph,$buffer);
echo $buffer;


?>
    <div class="container bgClankyContainer">
        <nav aria-label="breadcrumb" class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Úklid</a></li>
                <?php

                if(isset($thisClanek["tag_url2"])){
                    echo '<li class="breadcrumb-item"><a href="/'.$thisClanek["tag_url2"].'" title="'.$thisClanek["tag2"].'">'.$thisClanek["tag2"].'</a></li>';
                }
                ?>
                <?php

                if(isset($thisClanek["tag_url3"])){
                    echo '<li class="breadcrumb-item"><a href="/'.$thisClanek["tag_url3"].'" title="'.$thisClanek["tag3"].'">'.$thisClanek["tag3"].'</a></li>';
                }
                ?>
                
                <li class="breadcrumb-item active"><?= $thisClanek['topic'] ?></li>
            </ol>
        </nav>
        <div class="container text-left">
            <article class="clanky">
                <header>
                    <h1 class="h1Clanky"><?= $thisClanek['topic'] ?></h1>
                    <img src="<?= $thisClanek['image'] ?>" alt="<?= $thisClanek['topic'] ?>" class="clanekImg">
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                        <p class="textGreyCompany dateClanky text-left">
                            <time datetime="<?= $thisClanek['date-html'] ?>" pubdate="pubdate>">
                                Publikováno <?= $thisClanek['date'] ?>
                            </time>
                        </p>
                        <div class="yoo-zoo socialbuttons socialbuttonsDetail clearfix no-printed">
                            <div class="float-left">
                                <a href="https://twitter.com/intent/tweet?url=https://ekologickyuklid.eu/clanek/<?= $thisClanek['url'] ?>" class="twitter-share-button" data-show-count="false">Tweet</a>
                                <script async src="https://platform.twitter.com/widgets.js"></script>
                            </div>
                            <div class="float-left ml-2 mr-2">
                                <div class="g-plus" data-action="share" data-annotation="none" data-href="https://www.ekologickyuklid.eu/clanek/<?= $thisClanek['url'] ?>"></div>
                            </div>
                            <div class="float-left ml-2" style="margin-top: -4px;">
                                <div class="fb-share-button" data-href="https://ekologickyuklid.eu/clanek/<?= $thisClanek['url'] ?>" data-layout="button" data-size="small" data-mobile-iframe="false"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Sdílet</a></div>
                            </div>
                        </div>
                    </div>
                </header>
                <p class="light clankyPar">
                    <?= $thisClanek['body'] ?>
                </p>
                <?php
                if (!empty($thisClanek['video']))
                {
                    echo '<div class="rwd-object no-printed ml-5" mr-5><iframe class="rwd-object-in" src="';
                    echo $thisClanek['video'];
                    echo'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                }
                ?>
                <div class="d-flex justify-content-between ">
                    <span class="only-print">Vytištěno z webu www.ekologickyuklid.eu od <strong>EkoBIT</strong></span>
                    <img class="img-fluid only-print" src="/img/ekobit_logo.png" alt="Ekologický úklid">
                </div>

                <?php

                    if (!empty($thisClanek['odkaz']['url'])){
                        $urlBtn = $thisClanek['odkaz']['url'];
                    } else {
                        $urlBtn = "/uklidove-sluzby.php";
                    }

                if (!empty($thisClanek['odkaz']['title'])){
                    $titleBtn = $thisClanek['odkaz']['title'];
                } else {
                    $titleBtn = "Chci ekologický úklid!";
                }

                ?>



                <a href="<?php echo $urlBtn; ?>"  class="btn btn-primary link-move btn-dezinfekce no-printed mt-5"><?php echo $titleBtn; ?></a>



            </article>
        </div>
    </div>
    <div class="container-fluid bg-top-container no-printed">
        <div class="container">
            <h2>Podobné články</h2>
            <p class="par45b par45t text-center mb-0">Přečtěte si zajímavé rady a tipy, jak uklízet ekologicky a být maximální šetrný k přírodě a životnímu prostředí.</p>
            <div class="row align-items-start clankyRow">
                <?php
                foreach ($json_data as $clanky) {
                    foreach ($similar as $s){
                        if($clanky['url'] == $s){
                            ?>
                            <div class="col-md-6 clankyCol">
                                <div class="row divColsClanky divClanky">
                                    <div class="col-md-6">
                                        <a href="/clanek/<?= $clanky['url'] ?>"><img src="<?= $clanky['image'] ?>" alt="<?= $clanky['topic'] ?>" class="img-fluid clankyImg"></a>
                                        <div class="yoo-zoo socialbuttons clearfix">
                                            <div class="float-left">
                                                <a href="https://twitter.com/intent/tweet?url=https://ekologickyuklid.eu/clanek/<?= $clanky['url'] ?>" class="twitter-share-button" data-show-count="false">Tweet</a>
                                                <script async src="https://platform.twitter.com/widgets.js"></script>
                                            </div>
                                            <div class="float-left ml-2 mr-2 g-plusDiv">
                                                <div class="g-plus" data-action="share" data-annotation="none" data-href="https://www.ekologickyuklid.eu/clanek/<?= $clanky['url'] ?>"></div>
                                            </div>
                                            <div class="float-left fbDiv" style="margin-top: -4px;">
                                                <div class="fb-share-button" data-href="https://ekologickyuklid.eu/clanek/<?= $clanky['url'] ?>" data-layout="button" data-size="small" data-mobile-iframe="false"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Sdílet</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl5">
                                        <h3 class="h3Clanky"><?= $clanky['topic'] ?></h3>
                                        <p class="paragraphClanky"><?= $clanky['short'] ?></p>
                                        <a href="/clanek/<?= $clanky['url'] ?>" class="btn btnFullArticle btn-primary float-right">Celý článek</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 text-center">
                    <a href="/clanky" class="btn btn-primary btnClanky btn-black-form text-center cursorPointer"><strong>Všechny články</strong></a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(".clankyRow").find('.clankyCol:gt(1)').addClass('mt-10');
            $(".clankyRow").find('.fbDiv:gt(3)').addClass('divFb');
            size_div = $("#clanky .clankyCol").length;
            x = 6;
            $('#clanky .clankyCol:lt(' + x + ')').show();

            (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/platform.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
            })();
        });
    </script>
<?php
require_once "temp/footer.php";
?>
