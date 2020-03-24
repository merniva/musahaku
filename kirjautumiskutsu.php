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
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['nimi'], $_POST['salasana']) ) {
    // Could not get the data that should have been sent.
    http_response_code(400);
	exit('Täytäthän molemmat kentät!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $yhteys->prepare('SELECT id, salasana FROM kayttaja WHERE nimi = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['nimi']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        // Account exists, now we verify the password.
        if (password_verify($_POST['salasana'], $hash)) {
            // Verification success! User has loggedin!
            // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['nimi'] = $_POST['nimi'];
            $_SESSION['id'] = $id;
            http_response_code(200);
            echo "kayttajahaku.php";
           // header('Location: tietokantaharjoitus.php');
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