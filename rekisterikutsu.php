<?php
$dbpalvelin = getenv("PALVELIN");
$dbkayttaja =  getenv("KAYTTAJA");
$dbsalasana = getenv("SALASANA");
$db = "musahaku";
$yhteys = mysqli_connect($dbpalvelin, $dbkayttaja, $dbsalasana, $db);
if (mysqli_connect_errno()) {
    http_response_code(500);
	exit('Yhteyden muodostaminen epäonnistui: ' . mysqli_connect_error());
}

if (!isset($_POST['nimi'], $_POST['salasana'], $_POST['email'], $_POST['salasana2'])) {
    http_response_code(400);
	exit('Täytäthän kaikki lomakkeen tiedot!');
}

if ($stmt = $yhteys->prepare('SELECT id, salasana FROM kayttaja WHERE nimi = ? OR email = ?')) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        exit('Virheellinen sähköpostiosoite!');
    }
    if (preg_match('/[A-Za-z0-9]+/', $_POST['nimi']) == 0) {
        http_response_code(400);
        exit('Virheellinen käyttäjänimi!');
    }
    if (strlen($_POST['salasana']) < 6) {
        http_response_code(400);
        exit('Salasanan on oltava vähintään kuusi merkkiä pitkä!');
    }

    if (($_POST['salasana']) != ($_POST['salasana2'])) {
        http_response_code(400);
        exit('Salasana ei täsmää!');
    }

	// Sidotaan parametrit ja tarkistetaan, onko käyttäjänimi jo varattu
	$stmt->bind_param('ss', $_POST['nimi'], $_POST['email'],);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
	    http_response_code(400);
        echo 'Käyttäjänimi tai sähköpostiosoite on jo varattu, valitsethan toisen käyttäjänimen/sähköpostin!';
	} else {
		if ($stmt = $yhteys->prepare('INSERT INTO kayttaja (nimi, email, salasana) VALUES (?, ?, ?)')) {
            $stmt->bind_param('sss', $_POST['nimi'], $_POST['email'], $hash);
            $hash = password_hash($_POST['salasana'], PASSWORD_DEFAULT);
            $stmt->execute();
            http_response_code(200);
            echo 'Olet rekisteröitynyt onnistuneesti, nyt voit kirjautua sisään!';
        } else {
            http_response_code(500);
            echo 'Rekisteröityminen ei onnistunut!';
        }
	}
	$stmt->close();
} else {
    http_response_code(500);
    echo 'Pahoittelut, jotain meni pieleen!';
}

?>