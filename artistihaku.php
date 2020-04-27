<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Musahaku</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro:900|Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" type="text/css" href="musahaku.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="./funktiot.js"></script>
</head>
<body>
<div class='kehys'>
    <?php
        include 'linkkivalikko.php';
        naytaLinkkivalikko();
    ?>
    <div class='container'>
    <button onclick="siirraYlos()" id="ylos" title="ylos"><i class="fas fa-chevron-up"></i></button>
            <h2>Hae samankaltaisia artisteja <i class="fas fa-search"></i></h2><br>
            <form action="artistihaku.php" method="get" class="haku" id="artistihaku">
                <input name="nimi" class="hakukentta" id="hakukentta" placeholder="Anna artistin nimi" autocomplete="off"><br>
                <input type="submit" name="button" class="button" id="hakubutton" value="HAE">
            </form><br>
    </div>
    <div id='lataus'>
    </div>
    <div id='tulokset'>
    </div>
    <div class='sivunvaihto' id="sivunvaihto">
        <span><button type="button" class="sivubutton" id="edellinen"><<</button><span id="sivunro"></span><button type="button" class="sivubutton" id="seuraava">>></button></span>
    </div>
    <div id="infoModal" class="modal">
        <div class="modalSisalto">
            <p class="close" id="close">&times;
            </p>
            <h4 id="artistiOtsikko">Odota hetki...</h4>
            <p id="artistiInfo"></p><br>
            <h4>Genret: </h4>
            <div id='artistiGenreTagit'></div><br>
            <h4>Samankaltaisia artisteja:</h4>
            <div id="samankaltaiset"></div><br>
        </div>
    </div>
    <footer>
        <?php
            include 'footer.php';
        ?>
    </footer>
</div>

<script> 

// tee uusi haku infoboksista klikatun artistin mukaan
    window.uusiHaku = uusiHaku;

// info-ikkunan avaus
    function naytaInfo(artistiNimi) {
        document.getElementById("artistiInfo").innerHTML = "Ladataan...";
        var modal = document.getElementById("infoModal");
        var p = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
        p.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        document.getElementById("artistiOtsikko").innerHTML = artistiNimi;
        let artistiHaku = encodeURIComponent(artistiNimi);
        $.ajax({
            async:true,
            type: 'GET',
            url: `http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=${artistiHaku}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`,
            success: (payload) => {
                console.log(payload)
                document.getElementById("artistiInfo").innerHTML = payload.artist.bio.summary;
                document.getElementById("samankaltaiset").innerHTML = payload.artist.similar.artist
                    .map(({name,url})=>`<span><a onclick='uusiHaku("${name}")'>${name}</a></span>`).join(", ");
            }
        });
        let artistigenreurl = `http://ws.audioscrobbler.com/2.0/?method=artist.gettoptags&artist=${artistiHaku}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        $.ajax({
            async:true,
            type: 'GET',
            url: artistigenreurl,
            success: (payload) => {
                console.log(payload)
                let hakutulos = payload.toptags.tag.map(({name, url})=>`<span><a href="${url}">${name}</a></span>`);
                let ekatViisi = hakutulos.filter((tagi,index)=> index<5).join(", ");
                document.getElementById("artistiGenreTagit").innerHTML = ekatViisi;
            }
        });
    }


// linkkivalikko
    $(function() {
        $(".toggle").on("click", function() {
            if ($(".item").hasClass("active")) {
                $(".item").removeClass("active");
                $(this).find("a").html("<i class='fas fa-bars'></i>");
            } else {
                $(".item").addClass("active");
                $(this).find("a").html("<i class='fas fa-times'></i>");
            }
        });
    });

// scrollaa takaisin ylös -nappi
let scrollaaYlos = document.getElementById("ylos");
window.onscroll = function() {scrollFunction(scrollaaYlos)};


// haetaan tulokset
    function teeHaku(event) {
        document.getElementById('hakubutton').disabled = true;
        document.getElementById('tulokset').innerHTML = "";
        document.getElementById('lataus').innerHTML = "Ladataan...";
        event.preventDefault();
        let lomake = document.getElementById("artistihaku");
        let datalomake = new FormData(lomake);
        let nimi = datalomake.get("nimi");
        let artistihaku = `http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=${nimi}&limit=30&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        console.log(artistihaku);
        document.getElementById("tulokset").innerHTML = "";
        setTimeout(() => {
        document.getElementById('hakubutton').disabled = false;
        },  1000);
            $.ajax({
                async:true,
                type: 'GET',
                url: artistihaku,
                success: (payload) => {
                    console.log(payload)
                    if (payload.error) {
                        document.getElementById('lataus').innerHTML = "Yhtään tulosta ei löytynyt!";
                    } else {
                    payload.similarartists.artist.forEach((artist)=> naytaArtisti(artist, naytaInfo));
                    document.getElementById('lataus').innerHTML = "";
                    }
                }
            });
    }


// hae-buttonin aktivointi
    $(".haku").submit(function(event) {
        teeHaku(event);
    });
</script>
</body>
</html>
