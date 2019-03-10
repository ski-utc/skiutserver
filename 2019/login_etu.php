<?php
	session_start();

	// Ask CAS for user ticket
	// the service parameter is the callback address
	if(!isset($_GET['ticket'])) {
		header('Location: https://cas.utc.fr/cas/login?service=http://assos.utc.fr/skiutc/2019/login_etu.php?task=login');
	}

	// Ask for user info
	$response = file_get_contents("https://cas.utc.fr/cas/serviceValidate?service=http://assos.utc.fr/skiutc/2019/login_etu.php?task=login&ticket=".$_GET['ticket']);
	$response = new SimpleXMLElement($response);
	$response = $response->xpath('/cas:serviceResponse/cas:authenticationSuccess/cas:user');


	// Authenticated
	if($response) {
		$_SESSION['login-etu'] = (string) $response[0];
		include_once('db.php');

		// Fetch user info
		$user = $db->query('
			SELECT *
			FROM `users_2019`
			WHERE `login` = "'.$_SESSION['login-etu'].'"
		');
		//$_SESSION['user'] = $user->fetch();
		if( $user->rowCount() > 0) {
			$_SESSION['user'] = $user->fetch();
		}
		else {
			unset($_SESSION['login-etu']);
			unset($_SESSION['login-etu']);
			$_SESSION['login-notuser'] = true;
		} // Si il est pas dans la bdd on dégage sa session
		// Fetch authorized services for the current user (ex: the bureau members have access to the mail interface)
		$services = $db->query('
			SELECT `name`
			FROM `services`
			NATURAL JOIN `services-access`
			NATURAL JOIN `users_2019` AS u
			WHERE u.login = "'.$_SESSION['login-etu'].'"
		');
		$_SESSION['services'] = $services->fetchAll(PDO::FETCH_COLUMN, 0);
		//header('Location: ../temp/consultation_point.php');
		header('Location: ../index.php');
		exit();
	}
	else echo "fdp";
?>
