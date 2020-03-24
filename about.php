<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>About</title>
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
    <img src="dino.png" alt="logokuva" width="200" height="200"></img>
            <h2 id="aboutheader">Tietoa sivusta</h2>
            <div id="aboutboksi">
                <h3>[[[mikä?]]]</h3><br>
                <p>Sivusto on kouluprojektina tehty pieni musiikin tesaurus,
                jonka kautta voit etsiä kuunneltavaa musiikkigenren perusteella tai hakea artisteja samankaltaisuuteen perustuen.<br>
                Lisäksi kirjautuneena käyttäjänä voit tutkia muiden kuunnelluimpia artisteja ja albumeita last.fm -käyttäjänimen perusteella.<br><br>
                </p>
                <h3>[[[miksi?]]]</h3><br>
                <p>Sivusto syntyi <a href="https://www.last.fm/user/lumepuna">tekijänsä</a> tarpeesta löytää itselleen mielenkiintoista kuunneltavaa.
                Täältä voit etsiä niin vanhoja klassikoita kuin musiikkia kaupallisten soittolistojen ulkopuoleltakin.<br>
                Keskeisessä asemassa ovat yksittäisten kappaleiden tai listojen sijaan albumikokonaisuudet ja niiden takana olevat artistit.<br>
                <br></p>
                <h3>[[[miten?]]]</h3><br>
                <p>Tietojen haussa hyödynnetään <strong><a href="https://last.fm/">last.fm</a></strong> -palvelun avointa rajapintaa.<br>
                Sivun ulkoasussa käytetyt ikonit: <strong><a href="https://fontawesome.com/">Fontawesome.</a></strong><br>
                Taustakuva: <strong><a href="https://pixabay.com/fi/users/davidrockdesign-2595351/">DavidRockDesign.</a></strong>
                <br><br></p>
            </div>
    </div>
    <div class='footer'>
        <p>All the music information provided by <strong><a href="https://www.last.fm/">last.fm.</a></strong></p>
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
</script>
</body>
</html>
