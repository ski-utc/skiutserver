<?php
	session_start();
	include_once("db.php");
?>
<!doctype html>
<html>
<body>
  <div class=container>
<?php
		$mail = $_POST['mail'];

		// Fetch user info
		$email = $db->query('
			SELECT login
			FROM `users_2019`
			WHERE `login` = "'.$_POST['mail'].'"
		');
		echo $_POST['mail'];
		//$_SESSION['user'] = $user->fetch();
		if( $email->rowCount() > 0) {
			$_SESSION['login-tremplin'] = $_POST['mail'];
			echo $_SESSION['login-tremplin'];
			header('Location: ./index_chambre.php');
		}
		else {
			unset($_SESSION['login-tremplin']);
			unset($_SESSION['login-tremplin']);
			$_SESSION['login-notuser'] = true;
			header('Location: ./login_tremplin.php');
		} // Si il est pas dans la bdd on dégage sa session

		exit();

?>
    </div>
  </body>
</html>
