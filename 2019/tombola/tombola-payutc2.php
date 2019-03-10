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
$server = "https://assos.utc.fr/buckutt/";
$key = "e3a4936545d321b2567d977bcc325667";
$fun_id = "10";
$articles = array();
$articles["ticket"] = "4542"; // identifiant à ajouter

// Get user info, especially payment-type to know if online payment is authorized
$user = isset($_SESSION['login-etu']) ? getEtuFromLogin($db,$_SESSION['login-etu']) : getTremplinFromLogin($db,$_SESSION['login-tremplin']);

if(isset($_GET['tra_id'])) {
    $payutcClient = new AutoJsonClient(
		$server,
		"WEBSALE",
		array(),
		"Payutc Json PHP Client",
		"");
	$payutcClient->loginApp(array("key" => $key));
    
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

    $items[] = array($articles["ticket"], 1);

	$mail = isset($_SESSION['login-tremplin']) ? $_SESSION['login-tremplin'] : $user['email'];
	
	$force = isset($_SESSION['login-etu']) ? 'login' : 'direct'; 
	// Si l'user est etudiant on force l'usage de son compte payutc pour qu'il ai l'info dans son historique, 
	// pour un tremplin on lui fait gagné du temps en l'emmenant directement a la page CB
	$vente = $payutcClient->createTransaction(array(
		"items" => json_encode($items), 
		"fun_id" => $fun_id,
		"mail" => $mail,
		"return_url" => "http://assos.utc.fr/skiutc/2016/payment-payutc.php",
		"callback_url" => "http://assos.utc.fr/skiutc/2016/payment-payutc.php",
		"force" => $force
	));

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
