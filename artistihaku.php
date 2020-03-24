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
            <li class="item"><a href="about.php">About</a></li>
            <li class="item"><a href="index.php">Genret</a></li>
            <li class="item"><a href="artistihaku.php">Artistit</a></li>
            </li>
        <?php 
        if (isset($_SESSION['nimi'])) {
            echo "<li class=\"item\"><a href=\"kayttajahaku.php\">Hae käyttäjänimellä</a>
                    </li>
                    <li class=\"item\">
                    <i class=\"fas fa-sign-out-alt\" style=\"color:rgb(201, 0, 0);\"></i>
                    <a href=\"logout.php\" style=\"color:rgb(201, 0, 0);\">Kirjaudu ulos</a></p>
                    </li>
                    <li class=\"toggle\"><a href=\"#\"><i class=\"fas fa-bars\"></i></a></li>
                    </ul>";
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
        <button onclick="siirraYlos()" id="ylos" title="ylos"><i class="fas fa-chevron-up"></i></button>
    </div>
    <div class='container'>
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
    <div class='footer'>
        <p>All the music information provided by <strong><a href="https://www.last.fm/">last.fm.</a></strong></p>
    </div>
</div>

<script> 
// tee uusi haku infoboksista klikatun artistin mukaan
function uusiHaku(artisti) {
    let siivottuArtisti = artisti
        .replace(" & ", "&")
        .replace(" + ", "+");
        document.getElementById("close").click();
        document.getElementById("hakukentta").value = siivottuArtisti;
        document.getElementById("hakubutton").click();
        siirraYlos();
    }
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
                document.getElementById("artistiGenreTagit").innerHTML = payload.toptags.tag
                .map(({name,url})=>`<span><a href="${url}">${name}</a></span>`).filter((tagi,index)=> index<5).join(", ");
            }
        });
    }

// hakutulosten järjestäminen
    function naytaSamanKaltaiset(artisti) {
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
scrollaaYlos = document.getElementById("ylos");
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
    scrollaaYlos.style.display = "block";
  } else {
    scrollaaYlos.style.display = "none";
  }
}

function siirraYlos() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

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
                    payload.similarartists.artist.forEach((artist)=> naytaSamanKaltaiset(artist));
                    document.getElementById('lataus').innerHTML = "";
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
