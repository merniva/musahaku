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
</head>
<body>
<div class='kehys'>
    <nav>
    <ul class="menu">
            <li class="item"><a href="#">About</a></li>
            <li class="item"><a href="index.php">Genret</a></li>
            <li class="item"><a href="artistihaku.php">Artistit</a></li>
            </li>
        <?php 
        if (isset($_SESSION['nimi'])) {
            //echo "<p>Tervetuloa etsimään musiikkia, ". $_SESSION['nimi']."!<br><br>";
            echo "<li class=\"item\">
                    <i class=\"fas fa-sign-out-alt\" style=\"color:rgb(201, 0, 0);\"></i>
                    <a href=\"logout.php\" style=\"color:rgb(201, 0, 0);\">Kirjaudu ulos</a></p>
                    </li>";
        } else {
            echo "<li class=\"item\">
                <i class=\"fas fa-sign-in-alt\"></i>
                <a href=\"kirjaudu.php\">Kirjaudu</a>
            </li>
            <li class=\"item\">
                <i class=\"fas fa-user\"></i>
                <a href=\"rekisteroidy.php\">Rekisteröidy</a>
            </li>
            <li class=\"toggle\"><a href=\"#\"><i class=\"fas fa-bars\"></i></a></li>
        </ul>";
        }    
        ?>
    </nav>
    <div class='header'>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="error tai success">
                <h3><?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>
    </div>
    <div class='container'>
            <h2>Hae genren perusteella <i class="fas fa-search"></i></h2><br>
            <form action="index.php" method="get" class="haku" id="genrehaku">
                <input type="radio" id="albumi" name="genrevalinta" value="albumi" checked>
                    <label for="albumi">Hae albumeita &nbsp;</label>
                <input type="radio" id="artisti" name="genrevalinta" value="artisti">
                    <label for="artisti">Hae artisteja</label><br><br>
                <input name="nimi" class="hakukentta" placeholder="Anna genren nimi" autocomplete="off" required><br>
                <input type="submit" name="button" class="button" value="HAE"><br>
                <p id = "vinkkiboksi"><strong>Vinkki!</strong> Haethan vain yhtä genreä kerrallaan. <br>
                Kokeile hakua esimerkiksi musiikkityyleittäin (esim. <i>post-punk</i>), soittimittain (esim. <i>brass</i>) tai maittain (esim. <i>japanese</i>).
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
            <h4 id="infoOtsikko">Odota hetki...</h4><br>
            <p id="infoSisalto"></p><br>
            <h5 id="lisaOtsikko"></h5>
            <ul id="lisaInfo"></ul>
            <p class="close">&times;
            </p>
        </div>
    </div>
    <div class='footer'>
        <p>Tämä on footer</p>
    </div>
</div>

<script> 
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
        $.ajax({
            async:true,
            type: 'GET',
            url: `http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=${artistiNimi}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`,
            success: (payload) => {
                console.log(payload)
                // näytetään artistibio ja samankaltaiset artistit
                document.getElementById("infoSisalto").innerHTML = payload.artist.bio.summary;
                document.getElementById("lisaOtsikko").innerHTML ="Samankaltaisia artisteja:";
                document.getElementById("lisaInfo").innerHTML = payload.artist.similar.artist
                .map(({name,url})=>`<li><a href="${url}">${name}</a></li>`).join("");
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
        $.ajax({
            async:true,
            type: 'GET',
            url: `http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=b7ba2a47c41146f14422726a121f27b7&artist=${artistiNimi}&album=${albumiNimi}&format=json`,
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
    }

// artistihakutulosten järjestäminen
    function naytaArtisti(artisti) {
        let laatikko = document.createElement("div");
        laatikko.innerHTML = `
            <div class='tuloslaatikko'>
            <a>
                <img src="black-1296338_640.png" alt="artistin default-kuva" width="150" height="150"></img>
                <h3>${artisti.name}</h3>
            </a>
            <button id="katsoLisaa">Katso lisää</button>
            </div>
            `;
        laatikko.onclick=()=>naytaInfo(artisti.name)
        document.getElementById('tulokset').appendChild(laatikko)
    }

// albumihakutulosten järjestäminen
    function naytaAlbumi(albumi) {
        let laatikko = document.createElement("div");
        laatikko.innerHTML = `
            <div class='tuloslaatikko'>
            <a>
                <img src="${albumi.image[2]["#text"]}" width="160" height="160"></img>
                <h3>${albumi.artist.name}:<br></h3>
                <p class = "hakutulos">${albumi.name}</p>
            </a>
            <button id="katsoLisaa">Katso lisää</button>
            </div>
            `;
        laatikko.onclick=()=>albumiInfo(albumi.name, albumi.artist.name)
        document.getElementById('tulokset').appendChild(laatikko)
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

// sivunvaihto
    let sivu = 1;

    function next() {
        sivu++;
    }

    function back() {
        sivu--;
    }

    $("#edellinen").click(function(event){
        if (sivu > 1){
        back();
        teeHaku(event);
        }
    })

    $("#seuraava").click(function(event){
        next();
        teeHaku(event);
    })


// haetaan tulokset
    function teeHaku(event) {
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
        if (genre === "artisti") {
            $.ajax({
                async:true,
                type: 'GET',
                url: artistiurl,
                success: (payload) => {
                    console.log(payload)
                    payload.topartists.artist.forEach((artist)=> naytaArtisti(artist));
                    document.getElementById('lataus').innerHTML = "";
                }
            });
        } else if (genre === "albumi") {
            $.ajax({
                async:true,
                type: 'GET',
                url: albumiurl,
                success: (payload) => {
                    console.log(payload)
                    payload.albums.album.forEach((album)=> naytaAlbumi(album));
                    document.getElementById('lataus').innerHTML = "";
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
<?php

?>