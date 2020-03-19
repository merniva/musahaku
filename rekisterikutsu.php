<?php

$dbpalvelin = "localhost"; //"127.0.0.1:50713"; "localhost"
$dbkayttaja =  "Admin"; // "azure"; "Admin"
$dbsalasana = "admin1"; //"6#vWHD_$"; "admin1"
$db = "musahaku";  // "sakila";
$yhteys = mysqli_connect($dbpalvelin, $dbkayttaja, $dbsalasana, $db);
if (mysqli_connect_errno()) {
    http_response_code(500);
	exit('Yhteyden muodostaminen epäonnistui: ' . mysqli_connect_error());
}

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['nimi'], $_POST['salasana'], $_POST['email'], $_POST['salasana2'])) {
    http_response_code(400);
	exit('Täytäthän kaikki lomakkeen tiedot!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['nimi']) || empty($_POST['salasana']) || empty($_POST['email'])) {
	http_response_code(400);
	exit('Täytäthän kaikki lomakkeen tiedot!');
}

// We need to check if the account with that username exists.
if ($stmt = $yhteys->prepare('SELECT id, salasana FROM kayttaja WHERE nimi = ?')) {
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

	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['nimi']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
	    http_response_code(400);
		echo 'Käyttäjänimi on jo varattu, valitsethan toisen käyttäjänimen!';
	} else {
		if ($stmt = $yhteys->prepare('INSERT INTO kayttaja (nimi, email, salasana) VALUES (?, ?, ?)')) {
            // We do not want to expose passwords in our database, 
            //so hash the password and use password_verify when a user logs in.
            $stmt->bind_param('sss', $_POST['nimi'], $_POST['email'],$hash);
            $hash = password_hash($_POST['salasana'], PASSWORD_DEFAULT);
            $stmt->execute();
            http_response_code(200);
            echo 'Olet rekisteröitynyt onnistuneesti, nyt voit kirjautua sisään!';
        } else {
            // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
            http_response_code(500);
            echo 'Rekisteröityminen ei onnistunut!';
        }
	}
	$stmt->close();
} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
    http_response_code(500);
    echo 'Lisäys ei onnistunut!';
}

?>