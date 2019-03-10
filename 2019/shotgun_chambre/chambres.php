<?php
session_start();

include("db.php");
if(!isset($_SESSION['login-etu']) && !isset($_SESSION['login-tremplin']))
{
    header("Location: /skiutc/index.php");
    exit();
}
if (isset($_SESSION['login-etu']))
{$_SESSION['login']=$_SESSION['login-etu'];}
else if (isset($_SESSION['login-tremplin']))
{$_SESSION['login']=$_SESSION['login-tremplin'];}
$req = $db->query('SELECT * FROM users_2019 WHERE login = "' . $_SESSION['login'] . '" AND `payment-first-received` = 1');
//si le login n'est pas dans la table users ou pas payé
if (!$req->fetch()){
    header('location : index_chambre.php');
}

//si le login est déjà dans un groupe
$req = $db->query('SELECT id_rel_groupe_user FROM rel_groupe_user WHERE login_user = "' . $_SESSION['login'] . '"');
if ($req->fetch()){
    header( "refresh:0;url = chambres_erreur1.html");
}
$_SESSION['choix'] = true;
$_SESSION['form'] = false;
$_SESSION['secondes'] = 0;
$_SESSION['minutes'] = 5;
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Ski'UTC 2019 - Shotgun Chambres</title>
<link rel="shortcut icon" href="../images/logo.png">
<link rel="stylesheet" href="chambres.css">
</head>
<body>
<h1>Shotgun des chambres Ski'UTC 2019</h1>
        <!-- CHOIX DE LA TAILLE DE LA CHAMBRE -->
                <p>Seules les tailles encore disponibles sont affichées.</p>
                <p>Lors du Shotgun, certains peuvent bloquer une chambre puis ensuite annuler, ce qui la rendra à nouveau disponible ; reste à l'affût !</p>
				                <br />
        <p>Taille de la chambre :<p>
		<div id="bouton_accueil" >
                <?php
$chambres12 = $db->query('SELECT nb_libres FROM chambres_libres WHERE taille_chambre = 12')->fetch();
if($chambres12['nb_libres'] > 0) {
                ?>
                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="12" />
                </form>
                <?php
}
$chambres10 = $db->query('SELECT nb_libres FROM chambres_libres WHERE taille_chambre = 10')->fetch();
if($chambres10['nb_libres'] > 0) {
                ?>
                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="10" />
                </form>
                <?php
}
$chambres8 = $db->query('SELECT nb_libres FROM chambres_libres WHERE taille_chambre = 8')->fetch();
if($chambres8['nb_libres'] > 0) {
                ?>
                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="8" />
                </form>
                <?php
}
$chambres7 = $db->query('SELECT nb_libres FROM chambres_libres WHERE taille_chambre = 7')->fetch();
if($chambres7['nb_libres'] > 0) {
                ?>
                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="7" />
                </form>
                <?php
}
$chambres6 = $db->query('SELECT nb_libres FROM chambres_libres WHERE taille_chambre = 6')->fetch();
if($chambres6['nb_libres'] > 0) {
                ?>
                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="6" />
                </form>
                <?php
}
$chambres5 = $db->query('SELECT nb_libres FROM chambres_libres WHERE taille_chambre = 5')->fetch();
if($chambres5['nb_libres'] > 0) {
                ?>
                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="5" />
                </form>
                <?php
}
$chambres4 = $db->query('SELECT nb_libres FROM chambres_libres WHERE taille_chambre = 4')->fetch();
if($chambres4['nb_libres'] > 0) {
                ?>
                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="4" />
                </form>
                <?php
}
                ?>
                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="Autre" />
                </form>

                <form method="post" action="chambres_form.php">
                    <input class="btn" name="taille_" type="submit" value="Seul" />
                </form>
</div>

</body>
</html>
