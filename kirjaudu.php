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
<?php
        include 'linkkivalikko.php';
        naytaLinkkivalikko();
    ?>
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
        <p>Eikö sinulla ole vielä tunnuksia? </p>
        <p><strong><a href="rekisteroidy.php">Luo tunnukset tästä.</a></strong></p><br>
        </form>
        </div>
    </div>
    <div class='footer'>
        <?php
            include 'footer.php';
        ?>
    </div>
</div>


<script> 
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


    function piilotaVirhe(id, virhe){
                virhe.style.display = "none";
                document.getElementById(id).removeEventListener("click", virhe);
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
            window.location.href = "kayttajahaku.php";
            },
          error: function(error){
            var virhe = document.getElementById("virheviesti");
            virhe.innerHTML=`<p><strong>${error.responseText}</strong></p>`;
            virhe.style.display = "block";
            document.getElementById("nimi").value="";
            document.getElementById("salasana").value="";
            document.getElementById("nimi").addEventListener("click", () => piilotaVirhe("nimi", virhe))
          }
      })
    })
</script>
</body>
</html>