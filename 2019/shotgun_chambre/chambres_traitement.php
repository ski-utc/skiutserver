<?php

session_start();

include_once("db.php");
$autre=0;
$req = $db->query('SELECT * FROM users_2019 WHERE login = "' . $_SESSION['login'] . '" AND `payment-first-received` = 1');
if (!$req->fetch())
    header('location : ../../index.php');


// si on arrive sur la page comme il faut
if (isset($_POST['taille_']))
{
    foreach($_POST as $champ=>$valeur)
    { //on parcourt l'ensemble de champs de la variable $_POST, $champ étant leur nom de variable et  $valeur leur valeur
        if (substr($champ, 0, 6) == 'membre') // on fait un test sur les 6 premiers caractères du nom de la variable, si c'est "membre"
        {
            $req = $db->query('SELECT * FROM rel_groupe_user  WHERE login_user = "' . $valeur . '"'); //on récupère les lignes de rel_groupe_user qui contiennent déjà le login (ie la personne est déjà enregistrée)
            if ($req->fetch()) //si il existe de telles lignes, la saisie est fausse car on ne peut pas mettre qqln dans 2 chambres
            {
                $db->query('UPDATE chambres_libres SET nb_libres = nb_libres+1 WHERE taille_chambre = ' . $_POST['taille_']);
                header("location: chambres_erreur.php"); //dans ce cas là on repasse la chambre réservée comme chambre libre
                exit();
            }
        }
    }
	$taille = $_POST['taille_'];
	if ($_POST['taille_'] == 'Autre') //si on a choisi la taille autre
	{
        $autre=1;
        $taille=0;
        //gestion taille chambre si taille=autre
        foreach($_POST as $champ=>$valeur)
        {
            if (substr($champ, 0, 6) == 'membre' || substr($champ, 0, 11) == 'responsable') //on selectionne les variables membre ou responsable
            {
                if($valeur!='')//on teste leur valeur
	              {   $taille=$taille+1;	//on compte le nombre de personnes dans la chambre
	              }
            }
	    }
    }
    if ($taille == 6 || $taille == 7 || $taille == 8) //si la taille demandée correspond bien a une chambre
    {
        $dispo = $db->query('SELECT nb_saisies, nb_max FROM chambres_saisies WHERE taille = ' . $taille)->fetch(); //on récupère le nombre de chambres saisies et le nombre max de chambres possibles
        if ($dispo['nb_saisies'] >= $dispo['nb_max']) //on teste si le maximum est atteint
        {
            $db->query('UPDATE chambres_libres SET nb_libres = 0 WHERE taille = ' .$taille); //si c'est le cas on dit qu'il n'y a plus de chambres libres dans la taille demandée
            header("Location: chambres_erreur.php");
            exit();
        }
    }

    //if ($taille == 'Autre') $taille = count($_POST) - 7; // on retire les champs taille, soirees, voisins, demande, telephone, saisie, envoifinal

    if ($_SESSION['traitement'])
        header("Location: index_chambre.php");

    $_SESSION['traitement'] = true;

    $soirees_texte = $_POST['soirees_'];
    if ($soirees_texte == 'Grosse chouille') $soirees = 2;
    if ($soirees_texte == 'Chouille, mais pas que') $soirees = 1;
    if ($soirees_texte == 'Tranquille') $soirees = 0;

    if ($_POST['taille_'] != 'Seul') //si on est pas seul
    {
        $responsable = $_POST['responsable_'];
        if (isset($_POST['nom_'])) $nom = $_POST['nom_']; //on recup le nom de la chambre
        else $nom = '';

        $voisins = $_POST['voisins_'];
        $demande = $_POST['demande_'];
        $telephone = $_POST['telephone_'];

        //création d'un nouveau groupe
        $req = $db->prepare('INSERT INTO groupes (login_resp_groupe, nom, demande, telephone, taille, soirees, voisins, debut_saisie) VALUES ("' . $responsable . '", "' . $nom . '", "' . $demande . '", "' . $telephone . '", ' . $taille . ', ' . $soirees . ', "' . $voisins . '", "' . $_SESSION['heure'] . '")');
        $req->execute();

        $id_groupe_all = $db->query('SELECT id_groupe FROM groupes WHERE login_resp_groupe = "' . $responsable . '"')->fetch();
        $id_groupe = $id_groupe_all['id_groupe'];

        //création des relations groupe-user
        foreach($_POST as $champ=>$valeur)
        {
            if (substr($champ, 0, 6) == 'membre' || substr($champ, 0, 11) == 'responsable')
            {
                if($valeur!='')
                {
                    $req = $db->prepare('INSERT INTO rel_groupe_user (id_groupe, login_user) VALUES (' . $id_groupe . ', "' . $valeur . '")');
                    $req->execute();
                }
            }
        }

        if ($taille >= 4 && $taille <= 8 || $taille == 10 || $taille == 12)
        {
            //insertion login dans chambres
            $req = $db->prepare('INSERT INTO chambres (login_resp_groupe,taille) VALUES ("' . $responsable . '","' . $taille . '")');
            $req->execute();

            //récupération d'une chambre libre de la bonne taille   LIMIT 1 veut dire qu'on ne récupère qu'un résultat
            $chambre_all = $db->query('SELECT id_chambre FROM chambres WHERE login_resp_groupe = "' . $responsable . '"')->fetch();
            $id_chambre = $chambre_all['id_chambre'];

            //passage de la chambre à occupée
            $db->query('UPDATE chambres SET id_groupe= ' . $id_groupe . ', libre = false WHERE id_chambre = ' . $id_chambre);

            //ajout de la chambre au groupe créé
            $db->query('UPDATE groupes SET id_chambre = ' . $id_chambre . ' WHERE login_resp_groupe = "' . $responsable . '"');
            $db->query('UPDATE chambres SET id_groupe= ' . $id_groupe . ', full = true WHERE id_chambre = ' . $id_chambre);

            //ajout de la saisie de la chambre
            $db->query('UPDATE chambres_saisies SET nb_saisies = nb_saisies+1 WHERE taille = ' . $taille);
        }
    }



        /*

		//insertion login dans chambres
		$req = $db->prepare('INSERT INTO chambres (login_resp_groupe,taille) VALUES ("' . $responsable . '","' . $taille . '")');
        $req->execute();

        //récupèration de ce nouveau groupe
        $groupe = $db->query('SELECT id_chambre FROM chambres WHERE login_resp_groupe = "' . $responsable . '"')->fetch();
        $groupe = $groupe['id_chambre'];




        if ($taille == 6 || $taille == 10)
        {
            //récupèration d'une chambre libre de la bonne taille   LLIMIT 1 veut dire qu'on ne récupère qu'un résultat
			$chambre = $db->query('SELECT id_chambre FROM chambres WHERE taille = ' . $taille . ' AND full = false LIMIT 1')->fetch();
            $chambre = $chambre['id_chambre'];

            //passage de la chambre à occupée
            $db->query('UPDATE chambres SET id_groupe= ' . $groupe . ', libre = false WHERE id_chambre = ' . $chambre);


			$chambre = $db->query('SELECT id_groupe FROM groupes WHERE login_resp_groupe = "' . $responsable . '"')->fetch();
            $chambre = $chambre['id_groupe'];

			//ajout de la chambre au groupe créé
            $db->query('UPDATE groupes SET id_chambre = ' . $chambre . ' WHERE login_resp_groupe = "' . $responsable . '"');
			if ($autre!=1)
			{
		        $db->query('UPDATE chambres SET id_groupe= ' . $chambre . ', full = true WHERE id_chambre = ' . $chambre);
			}
			else{$db->query('UPDATE chambres SET id_groupe= ' . $chambre . ' WHERE id_chambre = ' . $chambre);}

            //ajout de la saisie de la chambre
            $db->query('UPDATE chambres_saisies SET nb_saisies = nb_saisies+1 WHERE taille = ' . $taille);

        }



        // mail, à compléter
        $sujet = "Chambre Ski'UTC";

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";

        $message = '';

        foreach($_POST as $champ=>$valeur)
        {
            if (substr($champ, 0, 6) == 'membre')
            {
				if($valeur!='')
		   {
                $destinataire = $db->query('SELECT etumail FROM users WHERE login = "' . $valeur . '"')->fetch();
                $destinataire = $destinataire['etumail'];

                $message = "Bonjour " . $valeur . ",<br><br>

                Tu viens d'être saisi dans une chambre pour Ski'UTC par " . $responsable . ".<br><br>

                Si tu penses qu'il y a erreur, contacte nous à skiutc@assos.utc.fr<br><br>

                À bientôt pour une semaine de folie !";

                mail($destinataire, $sujet, $message, $headers);
		   }
            } else if (substr($champ, 0, 6) == 'respon')
            {
                $destinataire = $db->query('SELECT etumail FROM users WHERE login = "' . $valeur . '"')->fetch();
                $destinataire = $destinataire['etumail'];

                $message = "Bonjour " . $valeur . ",<br><br>

                Tu viens de saisir une chambre pour Ski'UTC. Voici le récapitulatif de ta saisie :<br>
                Responsable : " . $valeur . "<br>
                Téléphone : " . $telephone . "<br>";
                if (isset($nom))
                    $message .= "Nom de la chambre : " . $nom . "<br>";
                $message .= "Soirées : " . $soirees_texte . "<br>
                    Voisins : " . $voisins . "<br>
                    Demandes : " . $demande . "<br>";
                $i = 1;
                foreach($_POST as $champ=>$valeur) {
                    if (substr($champ, 0, 6) == 'membre')
                    {
						if($valeur!='')
		   {
                        $message .= "Membre" . $i . " : " . $valeur . "<br>";
						$i = $i+1;
		   }
                    }
                }
                $message .= "<br>
                S'il y a une erreur, contacte nous à skiutc@assos.utc.fr<br><br>

                À bientôt pour une semaine de folie !";

                mail($destinataire, $sujet, $message, $headers);
            }
        }
        */

    else { // si seul
        $responsable = $_POST['responsable_'];
        $demande = $_POST['demande_'];

        //création d'un nouveau groupe
        $db->query('INSERT INTO groupes (login_resp_groupe, demande, taille, soirees) VALUES ("' . $responsable . '", "' . $demande . '", "1", ' . $soirees . ')');

        //récupèration de ce nouveau groupe
        $groupe = $db->query('SELECT id_groupe FROM groupes WHERE login_resp_groupe = "' . $responsable . '"')->fetch();
        $groupe = $groupe['id_groupe'];

        //création de la relation groupe-user
        $db->query('INSERT INTO rel_groupe_user (id_groupe, login_user) VALUES (' . $groupe . ', "' . $responsable . '")');
    }
}
else { //si on arrive "par hasard" sur cette page, redirection
    header("Location: chambres.php");
}
?>

<div class="container">
    <div class="chambres">
        <h1>Saisie effectuée !</h1>
        <a class="btn btn-danger" href="../../index.php">Retour</a>
    </div>
</div>
