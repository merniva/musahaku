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
            <h2 id="aboutheader">Otsikko</h2>
            <h3>Tietoa</h3><br>
            <h4>[[[mikä?]]]</h4>
            <p>Blaablaablaabllaa<br>
            Blaablaablaabllaa<br>
            Blaablaablaabllaa<br>
            Blaablaablaabllaa<br><br></p>
            <h4>[[[miksi?]]]</h4>
            <p>Blaablaablaabllaa<br>
            Blaablaablaabllaa<br>
            Blaablaablaabllaa<br><br></p>
            <h4>[[[credits]]]</h4>
            <p>Blaablaablaabllaa<br>
            Blaablaablaabllaa<br>
            Blaablaablaabllaa<br><br></p>
    </div>
    <div class='footer'>
        <p>Tämä on footer</p>
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
