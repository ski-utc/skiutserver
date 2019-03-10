<?php 
	session_start();
	
	include_once("db.php");
	include_once("user.php");

	include_once("JSonException.php");
      include_once("JSonClient.php");
        include_once("AutoJSonClient.php");
	use \Payutc\Client\AutoJsonClient;
use \Payutc\Client\JsonException;
	

// CONFIG DEV
//$server = "https://assos.utc.fr/payutc_dev/server/";
//$key = "7709f3596baa9156ff580070d5db3596";

// Config PROD
$server = "https://api.nemopay.net/services/";
$key = "7709f3596baa9156ff580070d5db3596";
$fun_id = "6";
$articles = array();
$articles["pack"   ] = "2393";
$articles["packT"  ] = "2394";
$articles["packE"  ] = "2395";
$articles["packST" ] = "2396";
$articles["packTST"] = "2397";
$articles["packEST"] = "2398";
$articles["ecoC"   ] = "2399";
$articles["ecoS"   ] = "2400";
$articles["ecoCS"  ] = "2401";
$articles["decC"   ] = "2402";
$articles["decS"   ] = "2403";
$articles["decCS"  ] = "2404";
$articles["senC"   ] = "2405";
$articles["senS"   ] = "2406";
$articles["senCS"  ] = "2407";
$articles["food"   ] = "2408";

// Get user info, especially payment-type to know if online payment is authorized
$user = isset($_SESSION['login-etu']) ? getEtuFromLogin($db,$_SESSION['login-etu']) : getTremplinFromLogin($db,$_SESSION['login-tremplin']);

if(($user['payment-first-received'] != 1 && $user['payment-type'] == 3) || isset($_GET['tra_id'])) {
	/*$payutcClient = new AutoJsonClient(
		$server,
		"WEBSALE",
		array(),
		"Payutc Json PHP Client",
		"");
	$payutcClient->loginApp(array("key" => $key));*/


 $payutcClient = new AutoJsonClient("https://api.nemopay.net/services/", "WEBSALE", array(CURLOPT_PROXY => 'proxyweb.utc.fr:3128', CURLOPT_TIMEOUT => 5), "Payutc Json PHP Client", array(), "payutc", "7709f3596baa9156ff580070d5db3596");
                
       /*         $arrayItems = array(array($billetDispo->getIdPayutc()));
                $item = json_encode($arrayItems);
                //return new Response($item);
                $billetIds = array();
                $billetIds[] = $billetCree->getTarif()->getIdPayutc();
                $returnURL = 'http://' . $_SERVER["HTTP_HOST"].$this->get('router')->generate('sdf_billetterie_routingPostPaiement',array('id'=>$billetCree->getId()));
                $callback_url = 'http://' . $_SERVER["HTTP_HOST"].$this->get('router')->generate('sdf_billetterie_callbackDePAYUTC',array('id'=>$billetCree->getId()));
                //return new Response($item);
                $c = $payutcClient->apiCall('createTransaction',
                    array("fun_id" => 10,
                        "items" => $item,
                        "return_url" => $returnURL,
                        "callback_url" => $callback_url,
                        "mail" => $userRefActif->getEmail()
                        ));

                $billetCree->setIdPayutc($c->tra_id);

                $em->persist($billetCree);
                $em->flush();*/


}

if(isset($_GET['tra_id'])) {
	// On vérifie l'état du paiement
	$transaction = $payutcClient->getTransactionInfo(array("fun_id" => $fun_id, "tra_id" => $_GET['tra_id']));
	
	// TODO : Save $transaction->status (if 'V' alors valider le paiement)
	$validated = $transaction->status == 'V' ? 1 : 0;

	$tra_id = $db->prepare('
		UPDATE `users`
		SET `tra_status`=:tra, `payment-first-received` ='.$validated.'
		WHERE `tra_id`=:id
		');
	$tra_id->bindParam(':tra',$transaction->status,PDO::PARAM_STR);
	$tra_id->bindParam(':id',$_GET['tra_id'],PDO::PARAM_INT);
	$tra_id->execute();
	
	// Il faudra ensuite retirer le print_r et le exit
	//print_r($transaction);
	//exit();

	if(!isset($_SESSION['login-etu']) && !isset($_SESSION['login-tremplin'])) {
		exit(); // Appel de serveur a serveur il n'y a rien a renvoyer.
	}
	// update $user
	$user = isset($_SESSION['login-etu']) ? getEtuFromLogin($db,$_SESSION['login-etu']) : getTremplinFromLogin($db,$_SESSION['login-tremplin']);
}

// Get user info, especially payment-type to know if online payment is authorized
$user = isset($_SESSION['login-etu']) ? getEtuFromLogin($db,$_SESSION['login-etu']) : getTremplinFromLogin($db,$_SESSION['login-tremplin']);

if($user['payment-first-received'] != 1 && $user['payment-type'] == 3) {

	// COnstruction de la liste d'article a facturer
	$items = array();
	if($user['transport'] == 2 && $user['transport-back'] == 2) {
		// No transport
		switch($user['type']) {
			case 0:
				$items[] = array($articles["packST"], 1);
				break;
			case 1:
				$items[] = array($articles["packTST"], 1);
				break;
			case 2:
				$items[] = array($articles["packEST"], 1);
				break;
		}
	} else {
		switch($user['type']) {
			case 0:
				$items[] = array($articles["pack"], 1);
				break;
			case 1:
				$items[] = array($articles["packT"], 1);
				break;
			case 2:
				$items[] = array($articles["packE"], 1);
				break;
			}
	}
	if($user['food'] != 2) {
		$items[] = array($articles["food"], 1);
	}
	if($user['equipment'] != 0) {
		if($user['pack'] == 0) {
			$name = "eco";
		} else if($user['pack'] == 1) {
			$name = "dec";
		} else if($user['pack'] == 2) {
			$name = "sen";
		}
		if($user['items'] == 0) {
                $name .= "C";
        } else if($user['items'] == 1) {
                $name .= "S";
        } else if($user['items'] == 2) {
                $name .= "CS";
        }
		$items[] = array($articles[$name], 1);
	}
	
	$mail = isset($_SESSION['login-tremplin']) ? $_SESSION['login-tremplin'] : $user['email'];
	
	// Ext
	$user = getExtFromLogin($db,$_SESSION['login-tremplin']);
	if($user)
	{ echo "TEST" ;print_r($user);
		if($user['transport'] == 2 && $user['transport-back'] == 2) {
			// No transport
			switch($user['type']) {
				case 0:
					$items[] = array($articles["packST"], 1);
					break;
				case 1:
					$items[] = array($articles["packTST"], 1);
					break;
				case 2:
					$items[] = array($articles["packEST"], 1);
					break;
			}
		} else {
			switch($user['type']) {
				case 0:
					$items[] = array($articles["pack"], 1);
					break;
				case 1:
					$items[] = array($articles["packT"], 1);
					break;
				case 2:
					$items[] = array($articles["packE"], 1);
					break;
				}
		}
		if($user['food'] != 2) {
			$items[] = array($articles["food"], 1);
		}
		if($user['equipment'] != 0) {
			if($user['pack'] == 0) {
				$name = "eco";
			} else if($user['pack'] == 1) {
				$name = "dec";
			} else if($user['pack'] == 2) {
				$name = "sen";
			}
			if($user['items'] == 0) {
					$name .= "C";
			} else if($user['items'] == 1) {
					$name .= "S";
			} else if($user['items'] == 2) {
					$name .= "CS";
			}
			$items[] = array($articles[$name], 1);
		}
	}
	
	$force = isset($_SESSION['login-etu']) ? 'login' : 'direct'; 
	// Si l'user est etudiant on force l'usage de son compte payutc pour qu'il ai l'info dans son historique, 
	// pour un tremplin on lui fait gagné du temps en l'emmenant directement a la page CB
	$vente = $payutcClient->createTransaction(array(
		"items" => json_encode($items), 
		"fun_id" => $fun_id,
		"mail" => $mail,
		"return_url" => "http://assos.utc.fr/skiutc/payment-payutc.php",
		"callback_url" => "http://assos.utc.fr/skiutc/payment-payutc.php",
		"force" => $force
	));

	// TODO Save $vente->tra_id with user
	if(isset($_SESSION['login-etu']))
	{
		$db->exec('
			UPDATE `users`
			SET `tra_id`='.$vente->tra_id.'
			WHERE `login`=\''.$_SESSION['login-etu'].'\''
			);
	}
	else
	{
		$db->exec('
			UPDATE `users`
			SET `tra_id`='.$vente->tra_id.'
			WHERE `login`=\''.$_SESSION['login-tremplin'].'\' AND `type`=1'
			);
	}
	
	header('Location: '.$vente->url);
	exit();
}

?>
<!doctype html>
<html>
<?php include_once("head.html"); ?>

<body>
	<?php include_once("header.php"); ?>
    <div class="container"><?php
		// The user must be logged in to pay online
        if(!isset($_SESSION['login-etu']) && !isset($_SESSION['login-tremplin'])) 
		{ ?>
            <h2>Connexion requise</h2>
            <p>Connecte-toi afin de procéder au paiement par PayUTC.</p><?php
		}
		else
		{
			
			// The user has already paid
			if($user['payment-first-received'] == 1)
			{ ?>
				<h4>Paiement effectué</h4>
				<p>Votre paiement PayUtc a bien été pris en compte</p><?php
			}
			else if(isset($_GET['tra_id'])) 
			{?>
            	<h2>Paiement annulé</h2>
                <p>
                	La transaction a été annulée. Contacte-nous s'il s'agit d'une erreur, ou si tu n'arrives pas à payer.
                </p><?php
			}
			// Online payment is not authorized
			else
			{ ?>
            	<h2>PayUTC non autorisé</h2>
                <p>
                	Tu dois avoir une raison valable (séjour à l'étranger, stage) afin de payer par PayUTC.
                    Si c'est le cas, contacte-nous à <a class="externalLink"href="mailto:skiutc@assos.utc.fr">skiutc@assos.utc.fr</a> .
                </p><?php
			}
		} ?>
    </div>
    <?php include_once("footer.php"); ?>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
</body>
</html>
