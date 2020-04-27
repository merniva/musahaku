<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Musasaurus</title>
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
            <h2>Hae genren perusteella <i class="fas fa-search"></i></h2><br>
            <form action="index.php" method="get" class="haku" id="genrehaku">
                <input type="radio" id="albumi" name="genrevalinta" value="albumi" checked>
                    <label for="albumi">Hae albumeita &nbsp;</label>
                <input type="radio" id="artisti" name="genrevalinta" value="artisti">
                    <label for="artisti">Hae artisteja</label><br><br>
                <input name="nimi" class="hakukentta" id="hakukentta" placeholder="Anna genren nimi" autocomplete="off" required><br>
                <input type="submit" name="button" class="button" id="hakubutton" value="HAE"><br>
                <div id= "vinkkiboksi">
                    <strong>Vinkki!</strong> Haethan vain yhtä genreä kerrallaan. <br>
                    <p>Kokeile hakua esimerkiksi musiikkityyleittäin (esim. <i>post-punk</i>),<br> soittimittain (esim. <i>violin</i>) tai vuoden perusteella (esim. <i>1990</i>).</p>
                </div>
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
            <div class="infoOtsikko" id="infoOtsikko">Odota hetki...</div><br>
            <div id="infoSisalto"></div><br>
            <h4>Genret: </h4>
            <div id='genreTagit'></div><br>
            <h5 id="lisaOtsikko"></h5>
            <ul id="lisaInfo"></ul>
        </div>
    </div>
    <footer>
        <?php
            include 'footer.php';
        ?>
    </footer>
</div>

<script>

// tee uusi haku infoboksista klikatun genren mukaan
    window.uusiHaku = uusiHaku;

// infoikkunan avaus
   function naytaInfo(artistiNimi) {
        document.getElementById("infoSisalto").innerHTML = "Ladataan...";
        var modal = document.getElementById("infoModal");
        var p = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
        // sulkeminen klikkauksella
        p.onclick = function() {
            modal.style.display = "none";
            document.getElementById("lisaOtsikko").innerHTML ="";
            document.getElementById("lisaInfo").innerHTML = "";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                document.getElementById("lisaOtsikko").innerHTML ="";
                document.getElementById("lisaInfo").innerHTML = "";
            }
        }
        document.getElementById("infoOtsikko").innerHTML = artistiNimi;
        // haetaan lisäinfo
        let artistiHaku = encodeURIComponent(artistiNimi);
        $.ajax({
            async:true,
            type: 'GET',
            url: `http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=${artistiHaku}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`,
            success: (payload) => {
                console.log(payload)
                // näytetään artistibio ja samankaltaiset artistit
                document.getElementById("infoSisalto").innerHTML = payload.artist.bio.summary;
                document.getElementById("lisaOtsikko").innerHTML ="Samankaltaisia artisteja:";
                document.getElementById("lisaInfo").innerHTML = payload.artist.similar.artist
                    .map(({name,url})=>`<li><a href="${url}">${name}</a></li>`).join("");
            }
        });
        let artistigenreurl = `http://ws.audioscrobbler.com/2.0/?method=artist.gettoptags&artist=${artistiHaku}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        $.ajax({
            async:true,
            type: 'GET',
            url: artistigenreurl,
            success: (payload) => {
                console.log(payload)
                let hakutulos = payload.toptags.tag.map(({name})=>`<span><a onclick='uusiHaku("${name}")'>${name}</a></span>`);
                let ekatViisi = hakutulos.filter((tagi,index)=> index<5).join(", ");
                document.getElementById("genreTagit").innerHTML = ekatViisi;
            }
        });
    }

// albumi-info
    function albumiInfo(albumiNimi, artistiNimi) {
        document.getElementById("infoSisalto").innerHTML = "Ladataan...";
        var modal = document.getElementById("infoModal");
        var p = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
        // sulkeminen klikkauksella
        p.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        document.getElementById("infoOtsikko").innerHTML = `${artistiNimi}: ${albumiNimi}`;
        // haetaan lisäinfo
        let artistiHaku = encodeURIComponent(artistiNimi);
        let albumiHaku = encodeURIComponent(albumiNimi);
        $.ajax({
            async:true,
            type: 'GET',
            url: `http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=b7ba2a47c41146f14422726a121f27b7&artist=${artistiHaku}&album=${albumiHaku}&format=json`,
            success: (payload) => {
                console.log(payload)
                // näytetään albumiwiki ja kappalelistaus (jos wikiä ei löydy, pelkkä kappalelistaus)
                if (payload.album.wiki) {
                    document.getElementById("infoSisalto").innerHTML = payload.album.wiki.summary;
                    document.getElementById("lisaInfo").innerHTML = "<h3>Kappaleet:</h3>"+payload.album.tracks.track
                    .map(({name})=>`<li>${name}</li>`).join("");
                } else {
                    document.getElementById("infoSisalto").innerHTML = "";
                    document.getElementById("lisaInfo").innerHTML = "<h3>Kappaleet:</h3>"+payload.album.tracks.track
                    .map(({name})=>`<li>${name}</li>`).join("");
                }
            }
        });
        let albumigenreurl = `http://ws.audioscrobbler.com/2.0/?method=album.gettoptags&artist=${artistiHaku}&album=${albumiHaku}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        $.ajax({
            async:true,
            type: 'GET',
            url: albumigenreurl,
            success: (payload) => {
                console.log(payload)
                let hakutulos = payload.toptags.tag.map(({name})=>`<span><a onclick='uusiHaku("${name}")'>${name}</a></span>`);
                let ekatViisi = hakutulos.filter((tagi,index)=> index<5).join(", ");
                document.getElementById("genreTagit").innerHTML = ekatViisi;
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


// sivunvaihto
let sivu = 1;

$("#edellinen").click(function(event){
    if (sivu > 1){
        sivu--;
    teeHaku(event);
    siirraYlos();
    }
})

$("#seuraava").click(function(event){
    sivu++;
    teeHaku(event);
    siirraYlos();
})


// haetaan tulokset
    function teeHaku(event) {
        document.getElementById('hakubutton').disabled = true;
        document.getElementById('tulokset').innerHTML = "";
        document.getElementById('lataus').innerHTML = "Ladataan...";
        document.getElementById('sivunvaihto').style.display = "flex";
        document.getElementById('sivunro').innerHTML = sivu;
        event.preventDefault();
        let lomake = document.getElementById("genrehaku");
        let datalomake = new FormData(lomake);
        let nimi = datalomake.get("nimi");
        let genre = datalomake.get("genrevalinta");
        let artistiurl = `http://ws.audioscrobbler.com/2.0/?method=tag.gettopartists&tag=${nimi}&page=${sivu}&limit=24&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        let albumiurl = `http://ws.audioscrobbler.com/2.0/?method=tag.gettopalbums&tag=${nimi}&page=${sivu}&limit=24&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        setTimeout(() => {
        document.getElementById('hakubutton').disabled = false;
        },  1000);
        if (genre === "artisti") {
            $.ajax({
                async:true,
                type: 'GET',
                url: artistiurl,
                success: (payload) => {
                    console.log(payload)
                    if (payload.topartists.artist.length === 0) {
                        document.getElementById('lataus').innerHTML = "Yhtään tulosta ei löytynyt!";
                    } else {
                    payload.topartists.artist.forEach((artist)=> naytaArtisti(artist, naytaInfo));
                    document.getElementById('lataus').innerHTML = ""; 
                    }
                }
            });
        } else if (genre === "albumi") {
            $.ajax({
                async:true,
                type: 'GET',
                url: albumiurl,
                success: (payload) => {
                    console.log(payload)
                    if (payload.albums.album.length === 0) {
                        document.getElementById('lataus').innerHTML = "Yhtään tulosta ei löytynyt!";
                    } else {
                    payload.albums.album.forEach((album)=> naytaAlbumi(album, albumiInfo));
                    document.getElementById('lataus').innerHTML = ""; 
                    }
                }
            });
        } else {}

    } 

// hae-buttonin aktivointi
    $(".haku").submit(function(event){
        sivu = 1;
        teeHaku(event);
    });
</script>
</body>
</html>
