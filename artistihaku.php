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
            //echo "<p>Tervetuloa etsimään musiikkia, ". $_SESSION['nimi']."!<br><br>";
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
                <input name="nimi" class="hakukentta" placeholder="Anna artistin nimi" autocomplete="off"><br>
                <input type="submit" name="button" class="button" value="HAE">
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

// sivunvaihto
/*let sivu = 1;

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
*/
// haetaan tulokset
    function teeHaku(event) {
        document.getElementById('tulokset').innerHTML = "";
        document.getElementById('lataus').innerHTML = "Ladataan...";
       // document.getElementById('sivunvaihto').style.display = "flex";
      //  document.getElementById('sivunro').innerHTML = sivu;
        event.preventDefault();
        let lomake = document.getElementById("artistihaku");
        let datalomake = new FormData(lomake);
        let nimi = datalomake.get("nimi");
        let artistihaku = `http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=${nimi}&limit=30&api_key=b7ba2a47c41146f14422726a121f27b7&format=json`;
        console.log(artistihaku);
        document.getElementById("tulokset").innerHTML = "";
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
       // sivu = 1;
        teeHaku(event);
    });
</script>
</body>
</html>
<?php

?>
