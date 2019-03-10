<?php

include_once("db.php");

session_start();

if (isset($_POST['taille']))
{$db->query('UPDATE chambres_libres SET nb_libres = (nb_libres+1) WHERE taille_chambre = ' . $_POST['taille']);
header("Location: chambres.php");}

?>
