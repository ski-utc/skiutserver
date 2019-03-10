<?php
	session_start();
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


		$ginger = new Ginger\Client\GingerClient("yE27aq9cV2Xdm79j85eNCEg3TJaEBZ8v");
		try {
			$gingerUser = $ginger->getUser($_POST['login']);

				if($gingerUser->is_cotisant){
					echo "Est cotisant";
				}

		}

		catch (Ginger\Client\ApiException $ex){
			echo "Erreur, login inexistant \n";
			//print $ex;
		}
		catch(Ginger\Client\Exception $e){
			echo "Error";
		}



	//header("Location: index.php");
?>
</body>
</html>
