<?php
include("db.php");
 // shotgun loupé
$shotgun_loupe = $db->query('select login from `shotgun-etu_2019` where login not in (select * from ( select login from `shotgun-etu_2019` limit 300) as t)');
while ($donnees2 = $shotgun_loupe->fetch())
{
 $login2=$donnees2[0];
// Préparation du mail contenant le lien d'activation
               $destinataire = "$login2@etu.utc.fr";
               $sujet = "SKI'UTC 18 - Votre resultat" ;

               //Headers pour faire passer du html en utf-8 + spécifier envoyeur
               $headers  = 'MIME-Version: 1.0' . "\r\n";
               $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
               $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";

               // Le lien d'activation est composé du login(log) et de la clé(cle)
               $message = 'Bonsoir,<br /><br />

               Voici votre résultat à Shotgun Ski\'UTC 2019 pour le semestre de A2018 : FX, INSUFFISANT <br />
               Ne vous inquiétez pas.. C\'est pas si difficile de se lever à 1h pour un shotgun physique !<br />
               Sinon tu peux aussi tenter ta chance avec la tombola : http://assos.utc.fr/skiutc/2018/tombola.php<br />
               Vous allez recevoir bientôt un autre mail avec plus d\'informations.<br /><br />
               Ski\'UTC 2019 qui vous aime quand même <3<br /><br />
               ---------------<br />
               Ceci est un mail automatique, Merci de ne pas y répondre.';


               mail($destinataire, $sujet, $message, $headers) ; // Envoi du mail
     echo $destinataire;
   }?>
