<?php

session_start();

include("db.php");

$req = $db->query('SELECT * FROM users_2019 WHERE login = "' . $_SESSION['login'] . '" AND `payment-first-received` = 1');
if (!$req->fetch())
{
    header('location : ../../index.php');
}

if(isset($_POST['saisie'])) {

    session_start();

    if ($_SESSION['recap'] || !$_SESSION['form'])
    {
        header('Location: chambres.php');
    }

    $_SESSION['form'] = false;
    $_SESSION['recap'] = true;
    $_SESSION['traitement'] = false;

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Shotgun Chambres Skiutc 2019</title>
	<link rel="stylesheet" href="chambres.css">
  </head>
  <body>
<div class="container">
    <div class="chambres">
        <h1>Vérification des informations</h1>
        <h2>La chambre est réservée. Il te reste <span id="time"><?php echo $_SESSION['minutes'] . ':' . $_SESSION['secondes']; ?></span> minutes pour la remplir avant l'annulation automatique.</h2>
        <h2>Vérifiez ces informations avant de valider. La saisie est irréversible, les membres de ce groupe ne pourront pas en changer.</h2>
<table>
   <tr>
       <td>
			<fieldset class="a">
			<legend> Informations générales : </legend>

        <label>Nom de la chambre :</label> <?php echo $_POST['nom_']; ?> <br />
        <label>Soirées :</label> <?php echo $_POST['soirees_']; ?> <br />
        <label>Voisins :</label> <?php echo $_POST['voisins_']; ?> <br />
        <label>Demandes : </label> <?php echo $_POST['demande_']; ?> <br />
        <label>Téléphone :</label> <?php echo $_POST['telephone_']; ?>  <br />

		</fieldset>
			</td>
			<td>
			<fieldset class="b">
			<legend> Composition de la chambre : </legend>
			<label>Responsable du groupe :</label> <?php echo $_POST['responsable_']; ?> <br />
        <label>Membre 2 :</label> <?php echo $_POST['membre1_']; ?> <br />
        <?php
    if(isset($_POST['membre2_'])) {
        echo '<label>Membre 3 :</label> ' . $_POST['membre2_'] . '<br />';
        if(isset($_POST['membre3_'])) {
            echo '<label>Membre 4 :</label> ' . $_POST['membre3_'] . '<br />';
            if(isset($_POST["membre4_"])) {
                echo '<label>Membre 5 :</label> ' . $_POST["membre4_"] . '<br />';
                if(isset($_POST['membre5_'])) {
                    echo '<label>Membre 6 :</label> ' . $_POST['membre5_'] . '<br />';
                    if(isset($_POST['membre6_'])) {
                        echo '<label>Membre 7 :</label> ' . $_POST['membre6_'] . '<br />';
                        if(isset($_POST['membre7_'])) {
                            echo '<label>Membre 8 :</label> ' . $_POST['membre7_'] . '<br />';
                            if(isset($_POST['membre8_'])) {
                                echo '<label>Membre 9 :</label> ' . $_POST['membre8_'] . '<br />';
                                if(isset($_POST['membre9_'])) {
                                   echo '<label>Membre 10 :</label> ' . $_POST['membre9_'] . '<br />';
                                   if(isset($_POST['membre10_'])) {
                                      echo '<label>Membre 11 :</label> ' . $_POST['membre10_'] . '<br />';
                                      if(isset($_POST['membre11_'])) {
                                         echo '<label>Membre 12 :</label> ' . $_POST['membre11_'] . '<br />';

                                      }
                                   }
                                }
                             }
                        }
                    }
                }
            }
        }
    }
        ?></td><td>

        <!-- Formulaire à champs cachés permettant de valider la saisie -->
        <form style="margin: 0 0 0 0;" method="post" action="chambres_traitement.php">
		<input name="taille_" type="hidden" value="<?php echo $_POST['taille_']; ?>" />
            <?php foreach ($_POST as $champ=>$valeur)
            echo '<input name="' . $champ . '" type="hidden" value="' . $valeur . '" />';
            ?>
            <input class="btn" name="envoifinal" type="submit" value="Valider" onclick="clicbouton();" />
        </form>

        <!-- Formulaire à champs cachés permettant de revenir en arrière -->
        <form style="margin: 0 0 0 0;" method="post" action="chambres_form.php">
            <?php foreach ($_POST as $champ=>$valeur) {
                if ($champ != 'saisie') {
                    echo '<input name="' . $champ . '" type="hidden" value="' . $valeur . '" />';
                }
            }
            ?>
            <input class="btn" name="modifier" type="submit" value="Modifier" onclick="clicbouton();" />
            <a class="btn" href="chambres.php" onclick="retour(); clicbouton();" id="retour">Retour</a>
			</td></tr></table>
			</fieldset>
        </form>
    </div>
</div>

<script type="text/javascript">
    function retour(){
        $.ajax({
            type: "POST",
            url: "deblocage_chambre.php",
            data: "taille="+<?PHP echo $_POST['taille_']; ?>
        });
        };
</script>

            <script type="text/javascript">
            function session(){
                        type: "POST",
                        url: "deblocage_chambre.php",
                        data: "taille="+<?PHP echo $_POST['taille_']; ?>,
                        async: false;
                    window.location=\'chambres.php\\';
                }

        </script>

<script type="text/javascript">
    var secondes = <?php echo $_SESSION['secondes'] ?>;
    var minutes = <?php echo $_SESSION['minutes'] ?>;
    var interval = setInterval(function(){
        secondes--;
        if (secondes < 0) {
            minutes--;
            secondes = 59;
        }

        var time = document.getElementById("time");

        time.textContent = this.minutes + ':' + this.secondes;

        if (this.minutes == 0 && this.secondes == 0) {
            clearInterval(interval);
            $.ajax({
                type: "POST",
                url: "deblocage_chambre.php",
                data: "taille="+<?PHP echo $_POST['taille_']; ?>,
                async: false
            });
            document.location.href="chambres.php";
        }
    },1000);
</script>

<script type="text/javascript">
    window.onbeforeunload = close; //à la sortie ou fermeture de la page

    var quitte = true; //permet de tester si la page est quittée via bouton ou non

    function close() {
        if (quitte){ //fenêtre fermée : on libère la chambre
            $.ajax({
                type: "POST",
                url: "deblocage_chambre.php",
                data: "taille="+<?PHP echo $_POST['taille_']; ?>,
                success: function(retour) {},
                async: false
                   });
        } else { // clic sur bouton : on màj le temps de saisie
            $.ajax({
                type: "POST",
                url: "deblocage_chambre.php",
                data: "secondes=" + secondes + "&minutes=" + minutes,
                success: function(retour) {},
                async: false
            });
        }
    }

    function clicbouton(){
        quitte = false;
    }
</script>

<?php
} else {
    header("Location: chambres.php");
}

?>

<script type="text/javascript" src="js2/jquery.js"></script>
</body>
</html>
