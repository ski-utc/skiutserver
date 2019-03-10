<?php
	session_start();

	// Ask CAS for user ticket
	// the service parameter is the callback address
	if(!isset($_GET['ticket'])) {
		header('Location: https://cas.utc.fr/cas/login?service=http://assos.utc.fr/skiutc/controllers/login-etu-tombola.php?task=login');
	}

	// Ask for user info
	$response = file_get_contents("https://cas.utc.fr/cas/serviceValidate?service=http://assos.utc.fr/skiutc/controllers/login-etu-tombola.php?task=login&ticket=".$_GET['ticket']);
	$response = new SimpleXMLElement($response);
	$response = $response->xpath('/cas:serviceResponse/cas:authenticationSuccess/cas:user');

	// Authenticated
	if($response) {
		$_SESSION['login-etu'] = (string) $response[0];

		include_once('../models/db.php');

		header('Location: ../2019/tombola/tombola.php');
		exit();
	}
?>
