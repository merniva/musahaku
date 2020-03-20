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
    <div class="kayttajalomake" >
        <div id="virheviesti"></div>
        <h2>Kirjaudu</h2><br>
        <p class="kayttajainfo"></p>
        <form action="kirjautumiskutsu.php" method="post" id="kirjaudu" class="kayttajalomake">
        <span>
            <label for="nimi">
                <i class="fas fa-user"></i>
            </label>
            <input name="nimi" id="nimi" class="kayttajakentta" type="text" placeholder="Anna käyttäjänimi" autocomplete="off" maxlength="25" required>
        </span>
        <span>
            <label for="salasana">
                <i class="fas fa-lock"></i>
            </label>
            <input name="salasana" id="salasana" class="kayttajakentta" type="password" placeholder="Anna salasana" autocomplete="off" required><br><br>
        </span>
        <input type="submit" value="Kirjaudu"class="kayttajabutton"><br><br>
        <p>Eikö sinulla ole vielä tunnuksia? <br><strong><a href="rekisteroidy.php">Luo tunnukset tästä.</a></strong></p>
        </form>
        </div>
    </div>
</div>


<script> 
    function piilotaVirhe(id){
                document.getElementById("virheviesti").innerHTML="";
                document.getElementById(id).removeEventListener("click");
            }
    $("#kirjaudu").submit(function(event) {
        event.preventDefault();
      let lomakedata = $('#kirjaudu').serialize();
      $.ajax({
          async:true,
          type: 'POST',
          url: $('#kirjaudu').attr('action'),
          data: lomakedata,
          success: function(response){
            window.location.href = "index.php";
            },
          error: function(error){
            document.getElementById("virheviesti").innerHTML=`<p>${error.responseText}</p>`;
            document.getElementById("nimi").value="";
            document.getElementById("salasana").value="";
            document.getElementById("nimi").addEventListener("click", () => piilotaVirhe("nimi"))
          }
      })
    })
</script>
</body>
</html>