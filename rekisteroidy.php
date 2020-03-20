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
        <h2>Rekisteröidy</h2><br>
        <p class="kayttajainfo"></p>
        <form action="rekisterikutsu.php" method="post" id="rekisteroidy" class="kayttajalomake" autocomplete="off">
            <span>
                <label for="username">
                    <i class="fas fa-user"></i>
                </label>
                <input type="text" name="nimi" id="nimi" class="kayttajakentta" placeholder="Anna käyttäjänimi"  maxlength="25" required><br>
            </span>
            <span>
                <label for="email">
                    <i class="fas fa-envelope"></i>
                </label>
                <input type="email" name="email" id="email" class="kayttajakentta" placeholder="Sähköpostiosoite" maxlength="50" required><br>
            </span>
            <span>
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input name="salasana" id="salasana" class="kayttajakentta" placeholder="Salasana" type="password" required><br>
            </span>
            <span>
                <label for="password2">
                    <i class="fas fa-lock"></i>
                </label>
            <input name="salasana2" id="salasana2" class="kayttajakentta" placeholder="Salasana uudestaan" type="password" required><br><br>
            </span>
            <input type="submit" value="Rekisteröidy" id="kayttajabutton" class="kayttajabutton"><br><br>
        <p>Onko sinulla jo tunnukset? <br><strong><a href="kirjaudu.php">Kirjaudu sisään tästä.</a></strong></p>
        </form>
        </div>
    </div>

</div>

<script> 
    function piilotaVirhe(id){
                document.getElementById("virheviesti").innerHTML="";
                document.getElementById(id).removeEventListener("click");
            }
    $("#rekisteroidy").submit(function(event) {
        event.preventDefault();
      let lomakedata = $('#rekisteroidy').serialize();
      $.ajax({
          async:true,
          type: 'POST',
          url: $('#rekisteroidy').attr('action'),
          data: lomakedata,
          success: function(response){
            alert("Käyttäjä lisätty, voit nyt kirjautua sisään!")
            window.location.href = "kirjaudu.php";
            },
          error: function(error){
            alert("Tietojen lähetys ei onnistunut!")
            console.log(error);
            document.getElementById("virheviesti").innerHTML=`<p>${error.responseText}</p>`;
            document.getElementById("nimi").value="";
            document.getElementById("email").value="";
            document.getElementById("salasana").value="";
            document.getElementById("salasana2").value="";
            document.getElementById("nimi").addEventListener("click", () => piilotaVirhe("nimi"))
          }
      })
    })
</script>
</body>
</html>