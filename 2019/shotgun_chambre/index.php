<?php
	session_start();?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Ski'UTC 2019 - Inscription Tremplin</title>
		<link rel="shortcut icon" href="2019/images/logo.png">
		<link rel="stylesheet" href="2019/shotgun_chambre/chambres.css">
	</head>
	<body>
		<h1>Shotgun des chambres Ski'UTC 2019</h1>
		<div class="bouton">

		<?php if(!isset($_SESSION['login-etu']) && !isset($_SESSION['login-tremplin']))
				{ echo "<h2>Vous devez vous connecter pour participer au shotgun.</h2>";
				  echo '<a href="2019/login_etu.php" class="btn">Etudiant</a>' ;
				  echo '<a href="2019/login_tremplin.php" class="btn">Tremplin</a>' ;}
			  else if(isset($_SESSION['login-etu']))
			  {
				 echo "<p>Tu es connecté avec le login : ";
				echo "<strong>".$_SESSION['login-etu']."</strong>";
				echo ".<br><br>Le shotgun apparaitra à 21h. Pense à réactualiser la page au moment du shotgun. La page sur laquelle t'envoie le bouton est la bonne.</p>";
				echo '<br><a href="2019/shotgun_chambre/chambres.php" class="btn">SHOTGUN</a>' ;
			  }
			  else if(isset($_SESSION['login-tremplin']))
			  {
				 echo "<p>Tu es connecté avec le login : ";
				echo "<strong>".$_SESSION['login-tremplin']."</strong>";
				echo ".<br><br>Le shotgun apparaitra à 21h. Pense à réactualiser la page au moment du shotgun. La page sur laquelle t'envoie le bouton est la bonne.</p>";
				echo '<br><a href="2019/shotgun_chambre/chambres.php" class="btn">SHOTGUN</a>' ;
			  }?>
		</div>
	</body>
</html>
