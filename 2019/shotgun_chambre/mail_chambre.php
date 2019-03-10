 <?php 
include("db.php");
/*$shotgun_reussi = $db->query("SELECT login_resp_groupe, id_groupe, taille FROM `chambres`");
		while ($donnees = $shotgun_reussi->fetch(PDO::FETCH_ASSOC))
		{
			  $login=$donnees['login_resp_groupe'];
                 $taille=$donnees['taille'];
                 $id_groupe = $donnees['id_groupe'];
                 echo $id_groupe;
                 $membres = $db->prepare('SELECT login_user FROM `rel_groupe_user` WHERE `id_groupe`=:id_groupe');
                    $membres->bindParam(':id_groupe',$id_groupe,PDO::PARAM_STR);
                    $membres->execute();
                    $membre = $membres->fetchAll(PDO::FETCH_ASSOC);
                    
                 
               // Préparation du mail contenant le lien d'activation
                 if(strlen($login)>8){
                    $destinataire = $login;
                 }
                 else{
                    $destinataire = "$login@etu.utc.fr";
                 }
                    $sujet = "SKI'UTC 18 - Chambres" ;
                    
                    //Headers pour faire passer du html en utf-8 + spécifier envoyeur
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";
         
                    // Le lien d'activation est composé du login(log) et de la clé(cle)
                    $message = 'Bonjour,<br /><br />

                    Vous avez shotgun une chambre de taille ' . $taille . '<br /><br />
                    Avec les membres suivants:<br />';
                    $i = 1;
                    foreach($membre as $row) {
                         $id = $row['login_user'];
                         //echo $id;
                         $message .= 'Membre' . $i . ' : ' . $id . '<br>';
                         $i = $i +1;
                    }
                    
                    $message .='Si vous avez un faux login dans votre chambre, vous n\'êtes pas garantis dans la chambre.<br>
                    S\'il y a une erreur, contacte nous à skiutc@assos.utc.fr<br><br>

					Ski\'UTC 2018 qui vous aime <3
                    ---------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.';
         
         
                    mail($destinataire, $sujet, $message, $headers) ; // Envoi du mail 
					echo $destinataire;
		} */

	//Autres	
$shotgun_reussi = $db->query("SELECT login_resp_groupe, id_groupe, taille FROM `groupes` WHERE taille<6");
          while ($donnees = $shotgun_reussi->fetch(PDO::FETCH_ASSOC))
          {
                 $login=$donnees['login_resp_groupe'];
                 $taille=$donnees['taille'];
                 $id_groupe = $donnees['id_groupe'];
                 echo $id_groupe;
                 $membres = $db->prepare('SELECT login_user FROM `rel_groupe_user` WHERE `id_groupe`=:id_groupe');
                    $membres->bindParam(':id_groupe',$id_groupe,PDO::PARAM_STR);
                    $membres->execute();
                    $membre = $membres->fetchAll(PDO::FETCH_ASSOC);
                    
                 
 // Préparation du mail contenant le lien d'activation
                 if(strlen($login)>8){
                    $destinataire = $login;
                 }
                 else{
                    $destinataire = "$login@etu.utc.fr";
                 }
                    $sujet = "SKI'UTC 18 - Chambres" ;
                    
                    //Headers pour faire passer du html en utf-8 + spécifier envoyeur
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";
         
                    // Le lien d'activation est composé du login(log) et de la clé(cle)
                    $message = 'Bonjour,<br /><br />

                    Voici ton groupe de ' . $taille . '<br /><br />
                    Avec les membres suivants:<br />';
                    $i = 1;
                    foreach($membre as $row) {
                         $id = $row['login_user'];
                         //echo $id;
                         $message .= 'Membre' . $i . ' : ' . $id . '<br>';
                         $i = $i +1;
                    }
                    
                    $message .='Nous allons vous placer dans une chambre avec les critères que vous avez donné.<br>
                    S\'il y a une erreur, contacte nous à skiutc@assos.utc.fr<br><br>

                         Ski\'UTC 2018 qui vous aime <3
                    ---------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.';
         
         
                    mail($destinataire, $sujet, $message, $headers) ; // Envoi du mail 
                         echo $destinataire;
          }


          ?>