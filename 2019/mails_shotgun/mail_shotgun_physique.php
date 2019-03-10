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
		$shotgun_loupe = $db->query('select login from `shotgun-etu_2018` where login not in (select * from ( select login from `shotgun-etu_2018` limit 292) as t)');
		while ($donnees2 = $shotgun_loupe->fetch())
		{
			$login2=$donnees2[0];
 // Préparation du mail contenant le lien d'activation
                    $destinataire = "$login2@etu.utc.fr";
                    $sujet = "SKI'UTC 18 - SHOTGUN PHYSIQUE" ;
                    
                    //Headers pour faire passer du html en utf-8 + spécifier envoyeur
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";
         
                    // Le lien d'activation est composé du login(log) et de la clé(cle)
                    $message = 'Salut à toi qui a raté de peu le shotgun !<br /><br />
                    On te redonne une chance d’avoir une place pour ce super voyage, et finir le semestre avec tes potes sur les pistes de Risoul !<br />
                    On remet en jeu des places pour les plus motivés. Pour ça il suffit de suivre les étapes suivantes :<br /><br />
                    -    A partir de 21h45 tu vas pouvoir retourner sur le site et cliquer sur le bouton Shotgun ou bien aller sur le lien suivant http://assos.utc.fr/skiutc/shotgun.html et entrer ton login. Tu seras alors mis dans la base de données des gens participant au shotgun physique.<br /><br />
                    -    A partir de midi demain tu pourras accéder à la page de choix des options sur le site et remplir tes informations. Tu pourras alors avoir le prix exact que tu auras à payer.<br /><br />
                    -    Dernière étape : JMDE mercredi à 16h ! Viens avec ton chèque (pour payer le voyage plus un chèque de caution de 200€). Les premiers inscrits auront leur place validée !<br /><br />
                    J’espère que tu réussiras cette quête !<br /><br />
                    (Mercredi c’est férié, et 16h c’est l’heure du gouter)<br /><br />
                    La team Ski’UTC 

                    ---------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.';
         
         
                    mail($destinataire, $sujet, $message, $headers) ; // Envoi du mail 
					echo $destinataire;
		}?>