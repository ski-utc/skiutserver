 <?php
include("db.php");
/*        $shotgun_reussi = $db->query('SELECT login FROM `shotgun-etu_2018` LIMIT 292');
		   while ($donnees = $shotgun_reussi->fetch())
		        {
			       $login=$donnees[0];
                    // Préparation du mail contenant le lien d'activation
                    $destinataire = "$login@etu.utc.fr";
                    $sujet = "SKI'UTC 18 - Informations" ;

                    //Headers pour faire passer du html en utf-8 + spécifier envoyeur
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";

                    // Le lien d'activation est composé du login(log) et de la clé(cle)

                    $message = 'Bienvenue dans l’aventure Ski’UTC toi qui a reçu une belle mention A!  😉 <br /><br />

                    La billeterie pour régler le séjour Ski\'UTC ouvre ce soir, mercredi 18 octobre sur le site de Ski\'UTC: http://assos.utc.fr/skiutc/ <br />
                    Elle sera ouverte jusqu\'au 30 octobre! <br />
                    Vous pourrez ajouter à votre place des options telles que la location de matériel de ski ou la réservation d’un food pack pour la semaine à Risoul. <br />
                    Toute place non payée le 30 octobre au soir sera remise en jeu pour le second shotgun! <br />
                    Les personnes ne pouvant pas régler par paiement en ligne mais par chèque devront envoyer un mail à Ski\'UTC, mais cela doit rester exceptionnel, privilégiez le paiement en ligne s\'il vous plait! <br />
                    Des permanences seront tenues à BF la lundi 30 et mardi 31 octobre pour récupérer les chèques de paiement (pour ceux qui n\'auront pas payé en ligne) et de caution.<br /><br />

                    Nous vous rappelons qu\'il n\'est pas possible de donner ou vendre sa place shotgunée à quelqu\'un d\'autre!<br /><br />
                    La bise givrée  😘 ❄️
                    La team Ski\'UTC
                    --------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.';


                    mail($destinataire, $sujet, $message, $headers) ; // Envoi du mail
					echo $destinataire;
		}*/

	//shotgun loupé
		$shotgun_loupe = $db->query('select email from `users_2019`');
		while ($donnees2 = $shotgun_loupe->fetch())
		{
			$mail=$donnees2[0];
 // Préparation du mail contenant le lien d'activation
                    $destinataire = "$mail";
                    $sujet = "SKI'UTC 19 - CHOIX DES OPTIONS" ;

                    //Headers pour faire passer du html en utf-8 + spécifier envoyeur
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";

                    // Le lien d'activation est composé du login(log) et de la clé(cle)
                    $message = 'Coucou et bienvenue dans l’aventure Ski’UTC toi qui a reçu une belle mention A!  😉<br /><br />

                    La billeterie pour régler le séjour Ski\'UTC ouvre aujourd\'hui sur le site de Ski\'UTC: http://assos.utc.fr/skiutc/ <br />
                    Tu peux aller sur le site rentrer tes infos et choix pour les packs !<br /><br />
                    Tu as jusqu\'au mardi 13 pour payer, sinon ta place sera remise en jeu lors du shotgun physique !<br />
                    Le paiement se fera par Pay\'UTC sur le site après avoir donné tes infos, ou par chèque (mais privilégie Pay\'UTC stp <3 )<br />
                    Pour information il y aura des frais de 4€ pour le paiement en ligne. <br /><br />

                    Si tu veux payer par chèque, il devra être à l\'ordre de BDE UTC SKIUTC avec le montant calculé sur le site. Des perms seront tenues à la rentrée pour récupérer les chèques !<br /><br />

                    N\'oublie pas de préparer un chèque de caution, qui devra être de 100€. On te tiendra au courant lorsque tu devras nous le donner ! <br /><br />


                    La bise givrée 😘 ❄️<br /><br />
                    La team Ski’UTC <br /><br /><br />

                    PS : Le shotgun des chambres se déroulera plus tard, lorsque tout le monde aura payé !<br />

                    ---------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.';


                    mail($destinataire, $sujet, $message, $headers) ; // Envoi du mail
					echo $destinataire;
		}?>
