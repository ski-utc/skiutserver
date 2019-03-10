<?php

//tableau à 2 dimensions contenant l'id en 0 et le mail en 1 de chaque ticket
$tab = array(array());

//ouverture du fichier csv
$fichier = fopen("xxx.csv", "r");

//stockage du fichier dans un tableau à 2 dim
$ligne=0;
while (($data = fgetcsv($fichier, 100, ",")) !== FALSE) {
    for ($i = 0 ; $i < 2 ; $i++) {
        $tab[$ligne][$i] = $data[$i];
    }
    $ligne++;
}

fclose($fichier);

$sujet = "Tombola Ski'UTC";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";

$compteur = 0;

//on parcourt tout le tableau
for ($i = 0 ; $i < count($tab) ; $i++) {
    $id = $tab[$i][0];
    $mail = $tab[$i][1];
    
    //si le mail existe (= n'a pas été déjà traité)
    if ($tab[$i][1] != null) {
        $destinataire = $mail;
        $message = 'Merci d\'avoir participer à la tombola de Ski\'UTC !<br /><br />
        
        Voici ton/tes numéro(s) de ticket :<br />';
        
        $message .= $id; 
        
        $compteur++;
        
        //on parcourt toute la suite du tableau pour trouver les autres tickets du même acheteur
        for ($j = $i+1; $j < count($tab); $j++) {
            if ($tab[$j][1] == $mail) {
                $message .= '<br />' . $tab[$j][0];
                $tab[$j][1] = null;
            }
        }
        
        $message .= '<br /> <br />Le tirage au sort se fera le mercredi 14 octobre à 12h30 au PIC.<br />
        Bonne chance à toi ! <br /><br />
        
        -------------<br />
        Ceci est un mail automatique, merci de ne pas répondre.';
        
        //envoi du mail
        mail($destinataire, $sujet, $message, $headers);
    }
}

echo $compteur . 'mails envoyes.';


?>