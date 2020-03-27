<?php
session_start();
$dbpalvelin = getenv("PALVELIN");
$dbkayttaja =  getenv("KAYTTAJA");
$dbsalasana = getenv("SALASANA");
$db = "musahaku";
$yhteys = mysqli_connect($dbpalvelin, $dbkayttaja, $dbsalasana, $db);
if (mysqli_connect_errno()) {
    http_response_code(500);
	exit('Yhteyden muodostaminen epäonnistui: ' . mysqli_connect_error());
}
if ( !isset($_POST['nimi'], $_POST['salasana']) ) {
    http_response_code(400);
	exit('Täytäthän molemmat kentät!');
}


if ($stmt = $yhteys->prepare('SELECT id, salasana FROM kayttaja WHERE nimi = ?')) {
	// Sidotaan parametrit ja tarkistetaan, löytyykö käyttäjä tietokannasta
	$stmt->bind_param('s', $_POST['nimi']);
	$stmt->execute();
	$stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        // Verifioidaan salasana ja jos onnistuu, luodaan sessio
        if (password_verify($_POST['salasana'], $hash)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['nimi'] = $_POST['nimi'];
            $_SESSION['id'] = $id;
            http_response_code(200);
            echo "kayttajahaku.php";
        } else {
            http_response_code(400);
            echo 'Virheellinen salasana!';
        }
    } else {
        http_response_code(400);
        echo 'Virheellinen käyttäjänimi!';
    }

	$stmt->close();
}
?>