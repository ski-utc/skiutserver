<?php

include_once("db.php");

    if(isset($_GET['query'])) {
        // Mot tapé par l'utilisateur
        $q = htmlentities($_GET['query']);

        // Requête SQL
        $requete = "SELECT login FROM users_2019 WHERE `payment-first-received` IS NOT NULL AND login LIKE '%". $q ."%' LIMIT 5";

        // Exécution de la requête SQL
        $resultat = $db->query($requete);

        // On parcourt les résultats de la requête SQL
        while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
            // On ajoute les données dans un tableau
            $suggestions['suggestions'][] = $donnees['login'];
        }

        if ($suggestions == null)
            $suggestions['suggestions'][] = $q;

        // On renvoie le données au format JSON pour le plugin
        echo json_encode($suggestions);
    }
?>
