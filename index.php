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
            <li class="item"><a href="#">Hae artistia</a></li>
            <li class="item"><a href="#">Selaa genrejä</a></li>
            </li>
            <li class="item button">
            <i class="fas fa-user"></i>
            <a href="#">Kirjaudu</a></li>
            <li class="item button"><a href="#">Rekisteröidy</a></li>
            <li class="toggle"><a href="#"><i class="fas fa-bars"></i></a></li>
        </ul>
    </nav>
    <div class='header'>
            <div class='logo'>
                <p>Tähän tulee logo</p>
            </div>
    </div>
    <div class='container'>
            <p>LoremipsumLoremipsumLoipsumLoremipsum</p><br>
            <form action="" method="get">
                <input name="nimi" class="hakukentta" placeholder="Hae musiikkia" autocomplete="off">
                <input type="submit" name="button" class="button" value="HAE">
                </form><br>
            <p>LoremipsumLoremipsumLoipsumLoremipsum</p><br>
    </div>
    <div class='footer'>
        <p>Tämä on footer</p>
    </div>
</div>
<script> 
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
<?php

?>