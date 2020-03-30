<?php
function naytaLinkkivalikko(){
    echo "<nav>
    <ul class=\"menu\">
            <li class=\"item\"><a href=\"about.php\">About</a></li>
            <li class=\"item\"><a href=\"index.php\">Genret</a></li>
            <li class=\"item\"><a href=\"artistihaku.php\">Artistit</a></li>
            </li>";
        if (isset($_SESSION['nimi'])) {
            echo "<li class=\"item\"><a href=\"kayttajahaku.php\">Hae käyttäjänimellä</a>
                    </li>
                    <li class=\"item\">
                    <i class=\"fas fa-sign-out-alt\"></i>
                    <a href=\"logout.php\">Kirjaudu ulos</a></p>
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
    echo "</nav>";
}

?>