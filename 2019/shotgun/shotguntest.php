<?php
	session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Ski-UTC 2018 Shotgun</title>
</head>

<body>
<?php
	// Charger ginger (pas nécessaire si vous utilisez Composer)
	require("autoload.php");
	if(isset($_POST['login']))
	{
		$ginger = new Ginger\Client\GingerClient("yE27aq9cV2Xdm79j85eNCEg3TJaEBZ8v");
		try {
            $gingerUser = $ginger->getUser(htmlentities($_POST['login']));
            print_r($gingerUser);
			echo $gingerUser->is_cotisant;

			}

		catch (Ginger\Client\ApiException $ex){
			echo "Erreur, login inexistant \n";
			//print $ex;

		}
		catch(Ginger\Client\Exception $e){
			echo "Error";
		}
	}
	else
	{
		echo "Entrez un login";
		//$_SESSION['shotgun'] = 6;
	}

	//header("Location: index.php");
?>
</body>
</html>
