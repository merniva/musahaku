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
            <li class="item">
                <i class="fas fa-sign-in-alt"></i>
                <a href="kirjaudu.php">Kirjaudu</a>
            </li>
            <li class="item">
                <i class="fas fa-user"></i>
                <a href="rekisteroidy.php">Rekisteröidy</a>
            </li>
            <li class="toggle"><a href="#"><i class="fas fa-bars"></i></a></li>
        </ul>
    </nav>
    <div class='header'>
    </div>
    <div class='container'>
            <h2>Hae samankaltaisia artisteja <i class="fas fa-search"></i></h2><br>
            <form action="artistihaku.php" method="get" class="haku" id="artistihaku">
                <input name="nimi" class="hakukentta" placeholder="Anna artistin nimi" autocomplete="off"><br>
                <input type="submit" name="button" class="button" value="HAE">
            </form><br>
    </div>
    <div id='tulokset'>
    </div>
    <div id="infoModal" class="modal">
        <div class="modalSisalto">
            <h4 id="artistiOtsikko">Odota hetki...</h4><br>
            <p id="artistiInfo"></p><br>
            <h5>Samankaltaisia artisteja:</h5>
            <ul id="samankaltaiset"></ul>
            <p class="close">&times;
            </p>
        </div>
    </div>
    <div class='footer'>
        <p>Tämä on footer</p>
    </div>
</div>
<script> 
// info-ikkunan avaus
    function naytaInfo(artistiNimi) {
        document.getElementById("artistiInfo").innerHTML = "Ladataan...";
        var modal = document.getElementById("infoModal");
        var p = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
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
        document.getElementById("artistiOtsikko").innerHTML = artistiNimi;
        $.ajax({
            async:true,
            type: 'GET',
            url: `http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=${artistiNimi}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`,
            success: (payload) => {
                console.log(payload)
                document.getElementById("artistiInfo").innerHTML = payload.artist.bio.summary;
                document.getElementById("samankaltaiset").innerHTML = payload.artist.similar.artist
                    .map(({name,url})=>`<li><a href="${url}">${name}</a></li>`).join("");
            }
        });
    }
// hakutulosten järjestäminen
    function naytaSamanKaltaiset(artisti) {
            let laatikko = document.createElement("div");
            laatikko.innerHTML = `
                <div class='tuloslaatikko'>
                <a href="${artisti.url}">
                    <img src="black-2403543_640.png" alt="artistin default-kuva" width="150" height="150"></img>
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

// samankaltaisten haku
    $(".haku").submit(function(event) {
        event.preventDefault();
        let lomake = document.getElementById("artistihaku");
        let datalomake = new FormData(lomake);
        let nimi = datalomake.get("nimi");
        let artistihaku = `http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=${nimi}&limit=48&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        console.log(artistihaku);
        document.getElementById("tulokset").innerHTML = "";
            $.ajax({
                async:true,
                type: 'GET',
                url: artistihaku,
                success: (payload) => {
                    console.log(payload)
                    payload.similarartists.artist.forEach((artist)=> naytaSamanKaltaiset(artist));
                }
            });
    });
</script>
</body>
</html>
<?php

?>
