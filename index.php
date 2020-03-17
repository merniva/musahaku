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
                <a href="#">Kirjaudu</a>
            </li>
            <li class="item">
                <i class="fas fa-user"></i>
                <a href="#">Rekisteröidy</a>
            </li>
            <li class="toggle"><a href="#"><i class="fas fa-bars"></i></a></li>
        </ul>
    </nav>
    <div class='header'>
            <div class='logo'>
                
            </div>
    </div>
    <div class='container'>
            <h2>Hae genren perusteella <i class="fas fa-search"></i></h2><br>
            <form action="index.php" method="get" class="haku" id="genrehaku">
                <input type="radio" id="albumi" name="genrevalinta" value="albumi" checked>
                    <label for="albumi">Hae albumeita &nbsp;</label>
                <input type="radio" id="artisti" name="genrevalinta" value="artisti">
                    <label for="artisti">Hae artisteja </label><br><br>
                <input name="nimi" class="hakukentta" placeholder="Anna genren nimi" autocomplete="off" required><br>
                <input type="submit" name="button" class="button" value="HAE">
            </form><br>
    </div>
    <div id='tulokset'>
    </div>
    <div class='footer'>
        <p>Tämä on footer</p>
    </div>
</div>

<script> 
    var sivu = 1; //&page=${sivu}
    // <img src=${artisti.image[1].#text}></img>
    //<h3>${albumi["@attr"].rank}</h3>
    //<h3>${artisti["@attr"].rank}</h3>
    function naytaArtisti(artisti) {
        let laatikko = document.createElement("div");
        laatikko.innerHTML = `
            <div class='tuloslaatikko'>
            <a href="${artisti.url}">
                <img src="black-2403543_640.png" alt="artistin default-kuva" width="200" height="200"></img>
                <h3>${artisti.name}</h3>
            </a>
            </div>
            `;
        document.getElementById('tulokset').appendChild(laatikko)
    }
    function naytaAlbumi(albumi) {
        let laatikko = document.createElement("div");
        laatikko.innerHTML = `
            <div class='tuloslaatikko'>
            <a href="${albumi.url}">
                <img src="${albumi.image[2]["#text"]}" width="180" height="180"></img>
                <h3>${albumi.artist.name}:<br></h3>
                <p>${albumi.name}</p>
            </a>
            </div>
            `;
        document.getElementById('tulokset').appendChild(laatikko)
    }
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

    $(".haku").submit(function(event) {
        event.preventDefault();
        let lomake = document.getElementById("genrehaku");
        let datalomake = new FormData(lomake);
        let nimi = datalomake.get("nimi");
        let genre = datalomake.get("genrevalinta");
        let artistiurl = `http://ws.audioscrobbler.com/2.0/?method=tag.gettopartists&tag=${nimi}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        let albumiurl = `http://ws.audioscrobbler.com/2.0/?method=tag.gettopalbums&tag=${nimi}&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        console.log(artistiurl, albumiurl);
        document.getElementById("tulokset").innerHTML = "";
        if (genre === "artisti") {
            $.ajax({
            async:true,
            type: 'GET',
            url: artistiurl,
            success: (payload) => {
                console.log(payload)
                payload.topartists.artist.forEach((artist)=> naytaArtisti(artist));
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
            }
            });
        } else {
            //ilmoitus tyhjästä kentästä
        }
    });
</script>
</body>
</html>
<?php

?>