<?php
	session_start();
	ini_set('display_errors', 'on');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Ski-UTC 2019 Shotgun</title>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55527513-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body>
<?php
	// Charger ginger (pas nécessaire si vous utilisez Composer)
	require("autoload.php");
	include_once("db.php");


	if(isset($_POST['login']))
	{
		$ginger = new Ginger\Client\GingerClient("yE27aq9cV2Xdm79j85eNCEg3TJaEBZ8v");
		try {
			$gingerUser = $ginger->getUser(htmlentities($_POST['login']));
			$req = $db->prepare('SELECT COUNT(*) FROM `shotgun-etu_2019` WHERE `login`=:login');
			$req->bindParam(':login',$_POST['login'],PDO::PARAM_STR);
			$req->execute();
			$req = $req->fetch();
			if($req[0] == 0)
			{
				if($gingerUser->is_cotisant == 1){
					$req = $db->prepare('INSERT INTO `shotgun-etu_2019`(`login`) VALUES (:login)');
					$req->bindParam(':login',$_POST['login'],PDO::PARAM_STR);
					$req->execute();

					echo "Votre shotgun a été pris en compte. Vous recevrez le résultat par mail avant minuit. Si vous ne recevez rien, contactez skiutc-info@assos.utc.fr.<br>";
					echo "<button type='button' href='../../index.html'>Retour à la page de shotgun</button>";
					?>
					<!-- <p>Votre inscription au shotgun a été prise en compte.<br> Vous pourrez vous connecter et choisir vos options à partir du 31/10/2017 à 13h. <br>Si vous ne pouvez pas vous connecter, contactez skiutc-info@assos.utc.fr.</p>
			<button type='button' onclick='window.location="../../index.php"'>Retour au site</button> -->
					<?php

					}

				else{
					echo "Utilisateur non cotisant";}
				}
				else{ ?><p>Votre inscription est déjà prise en compte.</p>
			<button type='button' onclick='window.location="../../index.html"'>Retour au site</button><?php
					}
			}

		catch (\Ginger\Client\ApiException $ex){
			echo "Erreur, login inexistant \n";
			//print $ex;

		}
		catch(\Ginger\Client\Exception $e){
			echo "Error";
		}
	}
	else
	{
		echo "Entrez un login";
		// $_SESSION['shotgun'] = 6;
	}

	//header("Location: index.php");
?>
</body>
</html>
